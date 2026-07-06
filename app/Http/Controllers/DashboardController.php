<?php

namespace App\Http\Controllers;

use App\Models\HousekeepingTask;
use App\Models\Unit;
use App\Models\User;
use App\Models\MaintenanceTask;
use App\Models\TaskCompletion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function adminDashboard()
    {
        $user = auth()->user();
        
        // Get today's data
        $todayCheckouts = \App\Models\GuestCheckout::whereDate('checkout_date', today())->count();
        $pendingTasks = HousekeepingTask::where('status', 'pending')->count();
        $completedToday = HousekeepingTask::where('status', 'completed')
            ->whereDate('completed_at', today())
            ->count();
        $availableStaff = User::where('role', 'staff')->count();
        
        return view('dashboard.admin', compact(
            'user',
            'todayCheckouts',
            'pendingTasks',
            'completedToday',
            'availableStaff'
        ));
    }

    /**
     * Show owner dashboard
     */
    public function ownerDashboard()
    {
        $user = auth()->user();
        
        // Get owner data
        $upcomingCheckouts = \App\Models\GuestCheckout::whereDate('checkout_date', '>=', today())->count();
        $cleaningInProgress = HousekeepingTask::where('status', 'pending')->count();
        $completedToday = HousekeepingTask::where('status', 'completed')
            ->whereDate('completed_at', today())
            ->count();
        
        return view('dashboard.owner', compact(
            'user',
            'upcomingCheckouts',
            'cleaningInProgress',
            'completedToday'
        ));
    }

    /**
     * Show staff dashboard
     */
    public function staffDashboard()
    {
        dd('Reached staffDashboard', auth()->user());
        $user = auth()->user();
        
        // Only allow staff, handyman, gardener
        if (!in_array($user->role, ['staff', 'handyman', 'gardener'])) {
            return redirect()->route('admin.dashboard');
        }
        
        // Get all pending tasks for this staff
        $housekeepingTasks = HousekeepingTask::where('assigned_staff_id', $user->id)
            ->where('status', 'pending')
            ->with('unit', 'cleaningTask')
            ->get();
        
        $maintenanceTasks = MaintenanceTask::where('assigned_staff_id', $user->id)
            ->where('status', 'pending')
            ->with('unit')
            ->get();
        
        $tasks = $housekeepingTasks->merge($maintenanceTasks);
        
        // Calculate stats
        $pendingCount = $tasks->count();
        
        $completedToday = HousekeepingTask::where('assigned_staff_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->count();
        
        $completedToday += MaintenanceTask::where('assigned_staff_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('completion_date', today())
            ->count();
        
        $totalCompleted = HousekeepingTask::where('assigned_staff_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        $totalCompleted += MaintenanceTask::where('assigned_staff_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        $totalTasks = $pendingCount + $totalCompleted;
        $completionRate = $totalTasks > 0 ? round(($totalCompleted / $totalTasks) * 100) : 0;
        
        return view('dashboard.staff', compact('tasks', 'pendingCount', 'completedToday', 'completionRate'));
    }
}