<?php

namespace App\Http\Controllers;

use App\Models\TaskCompletion;

class TaskApprovalController extends Controller
{
    public function index()
    {
        $completions = TaskCompletion::with([
            'staff',
            'housekeepingTask.unit',
            'housekeepingTask.cleaningTask',
            'maintenanceTask.unit'
        ])
        ->where('approval_status', 'pending')
        ->latest()
        ->get();

        return view('admin.approval.index', compact('completions'));
    }
}