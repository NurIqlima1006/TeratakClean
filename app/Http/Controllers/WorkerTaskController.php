<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\HousekeepingTask;
use App\Models\MaintenanceTask;
use Illuminate\Http\Request;

class WorkerTaskController extends Controller
{
    /**
     * Show all tasks for a specific worker.
     */
    public function show(User $user)
    {
        // Get housekeeping tasks
        $housekeepingTasks = HousekeepingTask::where('assigned_staff_id', $user->id)
            ->where('status', 'pending')
            ->with('unit', 'cleaningTask')
            ->get();
        
        // Get maintenance tasks
        $maintenanceTasks = MaintenanceTask::where('assigned_staff_id', $user->id)
            ->where('status', 'pending')
            ->with('unit')
            ->get();
        
        // Combine
        $tasks = $housekeepingTasks->merge($maintenanceTasks);
        
        return view('workers.tasks', compact('user', 'tasks'));
    }
}