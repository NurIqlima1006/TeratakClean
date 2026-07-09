<?php

namespace App\Http\Controllers;

use App\Models\HousekeepingTask;
use App\Models\MaintenanceTask;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Dashboard Cards
        $pendingTasks = HousekeepingTask::where('assigned_staff_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $completedTasks = HousekeepingTask::where('assigned_staff_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $assignedUnits = HousekeepingTask::where('assigned_staff_id', $user->id)
            ->distinct('unit_id')
            ->count('unit_id');

        $maintenanceTasks = MaintenanceTask::where('assigned_staff_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Current Tasks
        $todayTasks = HousekeepingTask::with(['unit', 'cleaningTask'])
            ->where('assigned_staff_id', $user->id)
            ->orderBy('guest_checkout_date')
            ->get();

        return view('staff.dashboard', compact(
            'pendingTasks',
            'completedTasks',
            'assignedUnits',
            'maintenanceTasks',
            'todayTasks'
        ));
    }
}