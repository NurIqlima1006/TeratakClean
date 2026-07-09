<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceTask;
use App\Models\Unit;
use App\Models\User;
use App\Helpers\RotationHelper;
use Illuminate\Http\Request;

class MaintenanceTaskController extends Controller
{
    /**
     * Create manual maintenance task (for urgent handyman work).
     */
    public function quickCreate(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'assigned_staff_id' => 'nullable',
        ]);

        // Determine who should receive the task
        $assignedStaffId = $validated['assigned_staff_id'] ?? null;

        // If "Housekeeping Staff (Auto)" is selected
        if ($assignedStaffId === 'housekeeping') {

            $housekeepingStaff = User::where('role', 'staff')->get();

            $leastBusy = null;
            $lowestTasks = PHP_INT_MAX;

            foreach ($housekeepingStaff as $staff) {

                $taskCount = MaintenanceTask::where('assigned_staff_id', $staff->id)
                    ->where('status', 'pending')
                    ->count();

                if ($taskCount < $lowestTasks) {
                    $lowestTasks = $taskCount;
                    $leastBusy = $staff;
                }
            }

            $assignedStaffId = $leastBusy?->id;
        }
        
        MaintenanceTask::create([
            'unit_id' => $validated['unit_id'],
            'task_name' => $validated['task_name'],
            'description' => $validated['description'],
            'assigned_staff_id' => $assignedStaffId,
            'status' => 'pending',
            'scheduled_date' => now(),
        ]);
        
        return redirect()->route('tasks.assignment')
            ->with('success', 'Maintenance task created! Refresh to see it in pending tasks.');
    }

    /**
     * Auto-generate rotation maintenance tasks for current week.
     */
    public static function autoGenerateRotationTasks()
    {
        $unit = RotationHelper::getUnitForCurrentWeek();
        
        if (!$unit) {
            return 0;
        }
        
        $gardeners = User::where('role', 'gardener')->get();
        $handymen = User::where('role', 'handyman')->get();
        
        $tasksCreated = 0;
        
        // Check if tasks already created for this week
        $existingTasks = MaintenanceTask::whereDate('created_at', '>=', now()->startOfWeek())
            ->where('unit_id', $unit->id)
            ->where('task_name', 'like', '%Gardening%')
            ->count();
        
        if ($existingTasks === 0) {
            // Create gardener task
            foreach ($gardeners as $gardener) {
                MaintenanceTask::create([
                    'unit_id' => $unit->id,
                    'task_name' => 'Gardening - Trim grass and trees',
                    'description' => 'Weekly gardening maintenance rotation',
                    'assigned_staff_id' => $gardener->id,
                    'status' => 'pending',
                    'scheduled_date' => now(),
                ]);
                $tasksCreated++;
            }
        }
        
        // Check handyman tasks
        $existingHandyman = MaintenanceTask::whereDate('created_at', '>=', now()->startOfWeek())
            ->where('unit_id', $unit->id)
            ->where('task_name', 'like', '%inspection%')
            ->count();
        
        if ($existingHandyman === 0) {
            // Create handyman task
            foreach ($handymen as $handyman) {
                MaintenanceTask::create([
                    'unit_id' => $unit->id,
                    'task_name' => 'Unit inspection - Check electrical, fixtures, etc.',
                    'description' => 'Weekly unit inspection and maintenance check',
                    'assigned_staff_id' => $handyman->id,
                    'status' => 'pending',
                    'scheduled_date' => now(),
                ]);
                $tasksCreated++;
            }
        }
        
        return $tasksCreated;
    }
}