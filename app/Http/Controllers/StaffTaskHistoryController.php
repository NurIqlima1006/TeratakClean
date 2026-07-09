<?php

namespace App\Http\Controllers;

use App\Models\TaskCompletion;

class StaffTaskHistoryController extends Controller
{
    public function index()
    {
        $history = TaskCompletion::with([
            'housekeepingTask.unit',
            'housekeepingTask.cleaningTask'
        ])
        ->where('staff_id', auth()->id())
        ->latest()
        ->paginate(10);

        return view('staff.history.index', compact('history'));
    }
}