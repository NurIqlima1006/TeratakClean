<?php

namespace App\Http\Controllers;

use App\Models\GuestCheckout;
use App\Models\HousekeepingTask;
use App\Models\MaintenanceTask;
use App\Models\User;

class DashboardController extends Controller
{
    public function adminDashboard()
{
    $pendingTasks = HousekeepingTask::where('status', 'pending')->count()
        + \App\Models\MaintenanceTask::where('status', 'pending')->count();

    $completedToday = HousekeepingTask::where('status', 'completed')
        ->whereDate('completed_at', today())
        ->count()
        + \App\Models\MaintenanceTask::where('status', 'completed')
        ->whereDate('completion_date', today())
        ->count();

    return view('admin.dashboard', [

        'todayCheckouts' => GuestCheckout::whereDate('checkout_date', today())->count(),

        'pendingTasks' => $pendingTasks,

        'completedToday' => $completedToday,

        'availableStaff' => User::whereIn('role', ['staff', 'handyman', 'gardener'])->count(),

    ]);
}

    public function ownerDashboard()
{
    $pendingTasks = HousekeepingTask::where('status', 'pending')->count()
        + \App\Models\MaintenanceTask::where('status', 'pending')->count();

    $completedToday = HousekeepingTask::where('status', 'completed')
        ->whereDate('completed_at', today())
        ->count()
        + \App\Models\MaintenanceTask::where('status', 'completed')
        ->whereDate('completion_date', today())
        ->count();

    return view('owner.dashboard', [

        'upcomingCheckouts' => GuestCheckout::whereDate('checkout_date', '>=', today())->count(),

        'cleaningInProgress' => $pendingTasks,

        'completedToday' => $completedToday,

    ]);
}
}