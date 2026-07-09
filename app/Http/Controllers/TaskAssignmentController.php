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

        $pendingApprovals = \App\Models\TaskCompletion::where('approval_status', 'pending')->count();
        return view(
    'tasks.assignment.index',
    compact(
        'pendingTasks',
        'staff',
        'staffWorkload',
        'pendingApprovals'
    )
);
    }

    /**
     * Perform auto-assignment using round-robin + min-heap algorithm.
     */
    public function autoAssign(Request $request)
{
    $tasks = HousekeepingTask::where('status', 'pending')
        ->whereNull('assigned_staff_id')
        ->get();

    $staff = User::where('role', 'staff')->get();

    if ($tasks->isEmpty()) {
        return back()->with('warning', 'No pending tasks.');
    }

    if ($staff->isEmpty()) {
        return back()->with('error', 'No staff found.');
    }

    $i = 0;

    foreach ($tasks as $task) {
        $task->update([
            'assigned_staff_id' => $staff[$i % $staff->count()]->id
        ]);

        $i++;
    }

    return back()->with('success', 'Tasks assigned successfully!');
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

    'approval_status' => 'pending',
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

    'approval_status' => 'pending',
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

            /**
         * Admin Approval Page
         */
        public function approval()
{
    $completions = \App\Models\TaskCompletion::with([
        'staff',
        'housekeepingTask.unit',
        'housekeepingTask.cleaningTask',
        'maintenanceTask.unit',
    ])
    ->where('approval_status', 'pending')
    ->latest()
    ->get();

    return view('admin.tasks.approval', compact('completions'));
}

        /**
         * Owner Approval Page
         */
        /**
 * Owner Approval Page
 */
public function ownerApproval()
{
    $completions = \App\Models\TaskCompletion::with([
        'staff',
        'housekeepingTask.unit',
        'housekeepingTask.cleaningTask',
        'maintenanceTask.unit'
    ])
    ->where('approval_status', 'pending')
    ->latest()
    ->get();

    return view('owner.tasks.approval', compact('completions'));
}

/**
 * Owner Task History
 */
public function ownerHistory()
{
    $completions = \App\Models\TaskCompletion::with([
        'staff',
        'housekeepingTask.unit',
        'housekeepingTask.cleaningTask',
        'maintenanceTask.unit'
    ])
    ->where('approval_status', 'approved')
    ->latest()
    ->get();

    return view('owner.tasks.history', compact('completions'));
}

/**
 * Approve completed task
 */
public function approve(\App\Models\TaskCompletion $completion)
{
    $completion->update([
        'approval_status' => 'approved',
        'approved_by' => auth()->id(),
        'approved_at' => now(),
    ]);

    return back()->with('success', 'Task approved successfully!');
}

/**
 * Display completed task history.
 */
public function history()
{
    $completions = \App\Models\TaskCompletion::with([
        'staff',
        'housekeepingTask.unit',
        'housekeepingTask.cleaningTask',
        'maintenanceTask.unit'
    ])
    ->where('approval_status', 'approved')
    ->latest()
    ->get();

    return view('admin.tasks.history', compact('completions'));
}
}