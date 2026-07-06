<?php

namespace App\Http\Controllers;

use App\Models\HousekeepingTask;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MaintenanceTask;
use App\Helpers\RotationHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TaskCompletion;

class TaskAssignmentController extends Controller
{
    /**
     * Show assignment interface with pending tasks.
     */
    public function index()
    {
        // Get housekeeping tasks (cleaning)
        $housekeepingTasks = HousekeepingTask::where('status', 'pending')
            ->where('assigned_staff_id', null)
            ->with('unit', 'cleaningTask')
            ->get();
    
        // Get maintenance tasks (gardener + handyman)
        $maintenanceTasks = MaintenanceTask::where('status', 'pending')
            ->where('assigned_staff_id', null)
            ->with('unit')
            ->get();
    
        // Combine both
        $pendingTasks = $housekeepingTasks->merge($maintenanceTasks);
    
        // Get all workers
        $staff = User::whereIn('role', ['staff', 'handyman', 'gardener'])->get();
    
        // Calculate workload for each worker
        $staffWorkload = [];
        foreach ($staff as $member) {
            $workload = HousekeepingTask::where('assigned_staff_id', $member->id)
                ->where('status', 'pending')
                ->count();
            $workload += MaintenanceTask::where('assigned_staff_id', $member->id)
                ->where('status', 'pending')
                ->count();
        
            $staffWorkload[$member->id] = [
                'name' => $member->name ?? $member->code,
                'code' => $member->code,
                'pending_tasks' => $workload,
            ];
        }
    
        return view('tasks.assignment.index', compact('pendingTasks', 'staff', 'staffWorkload'));
    }

    /**
     * Perform auto-assignment using round-robin + min-heap algorithm.
     */
    public function autoAssign(Request $request)
{
    // Get all pending housekeeping tasks
    $housekeepingTasks = HousekeepingTask::where('status', 'pending')
        ->where('assigned_staff_id', null)
        ->get();
    
    // Get all pending maintenance tasks
    $maintenanceTasks = MaintenanceTask::where('status', 'pending')
        ->where('assigned_staff_id', null)
        ->get();
    
    $allTasks = $housekeepingTasks->merge($maintenanceTasks);
    
    if ($allTasks->isEmpty()) {
        return redirect()->route('tasks.assignment')
            ->with('warning', 'No pending tasks to assign!');
    }
    
    // Get all staff
    $staff = User::whereIn('role', ['staff', 'handyman', 'gardener'])->get();
    
    if ($staff->isEmpty()) {
        return redirect()->route('tasks.assignment')
            ->with('error', 'No workers available for assignment!');
    }
    
    $tasksAssigned = 0;
    
    // Auto-assign each task
    foreach ($allTasks as $task) {
        // Find least loaded worker
        $leastLoadedStaff = null;
        $minWorkload = PHP_INT_MAX;
        
        foreach ($staff as $member) {
            $workload = HousekeepingTask::where('assigned_staff_id', $member->id)
                ->where('status', 'pending')
                ->count();
            $workload += MaintenanceTask::where('assigned_staff_id', $member->id)
                ->where('status', 'pending')
                ->count();
            
            if ($workload < $minWorkload) {
                $minWorkload = $workload;
                $leastLoadedStaff = $member;
            }
        }
        
        // Assign task
        if ($leastLoadedStaff) {
            $task->update(['assigned_staff_id' => $leastLoadedStaff->id]);
            $tasksAssigned++;
        }
    }
    
    return redirect()->route('tasks.assignment')
        ->with('success', "Successfully assigned {$tasksAssigned} tasks!");
}
/**
     * Generate weekly rotation tasks.
     */
    public function generateRotationTasks()
    {
        $tasksCreated = MaintenanceTaskController::autoGenerateRotationTasks();
        
        if ($tasksCreated > 0) {
            return redirect()->route('tasks.assignment')
                ->with('success', "Generated {$tasksCreated} weekly rotation tasks!");
        }
        
        return redirect()->route('tasks.assignment')
            ->with('info', 'Rotation tasks already generated for this week.');
    }
    /**
     * Export pending tasks as PDF
     */
    public function exportPdf()
    {
        // Get housekeeping tasks
        $housekeepingTasks = HousekeepingTask::where('status', 'pending')
            ->with('unit', 'cleaningTask', 'assignedStaff')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get maintenance tasks
        $maintenanceTasks = MaintenanceTask::where('status', 'pending')
            ->with('unit', 'assignedStaff')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $pendingTasks = $housekeepingTasks->merge($maintenanceTasks);
        
        $pdf = Pdf::loadView('tasks.pdf', compact('pendingTasks', 'housekeepingTasks', 'maintenanceTasks'));
        
        return $pdf->download('tasks-' . now()->format('Y-m-d') . '.pdf');
    }
    /**
     * Mark task as complete with evidence
     */
    public function completeTask(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|integer',
            'task_type' => 'required|string',
            'image' => 'required|image|max:5120', // 5MB
            'notes' => 'nullable|string',
        ]);

        try {
            $taskId = $validated['task_id'];
            $taskType = $validated['task_type'];
            $user = auth()->user();

            // Store image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('task-evidence', 'public');
            }

            if ($taskType === 'App\Models\HousekeepingTask') {
                $task = HousekeepingTask::findOrFail($taskId);
                $task->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

                // Create task completion record
                TaskCompletion::create([
                    'housekeeping_task_id' => $task->id,
                    'staff_id' => $user->id,
                    'image_path' => $imagePath,
                    'completion_notes' => $validated['notes'] ?? null,
                ]);
            } else {
                $task = MaintenanceTask::findOrFail($taskId);
                $task->update([
                    'status' => 'completed',
                    'completion_date' => now(),
                ]);

                // Create task completion record with maintenance task
                TaskCompletion::create([
                    'housekeeping_task_id' => null,
                    'maintenance_task_id' => $task->id,
                    'staff_id' => $user->id,
                    'image_path' => $imagePath,
                    'completion_notes' => $validated['notes'] ?? null,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Task completed!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
       
    /**
     * Display task assignments for owner (View Only)
     */
    public function ownerIndex()
    {
        // Housekeeping Tasks
        $housekeepingTasks = HousekeepingTask::with([
            'unit',
            'cleaningTask',
            'assignedStaff',
            'completion.staff'
        ])->get();

        // Maintenance Tasks
        $maintenanceTasks = MaintenanceTask::with([
            'unit',
            'assignedStaff',
            'completion.staff'
        ])->get();

        // Merge both task types
        $tasks = $housekeepingTasks->merge($maintenanceTasks);

        return view('owner.tasks.index', compact('tasks'));
    }
}