<?php

namespace App\Http\Controllers;

use App\Models\HousekeepingTask;
use App\Models\TaskCompletion;
use Illuminate\Http\Request;

class StaffTaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $search = request('search');

        $query = HousekeepingTask::with(['unit', 'cleaningTask'])
            ->where('assigned_staff_id', $user->id);

        if ($search) {

            $query->where(function ($q) use ($search) {

                $q->whereHas('cleaningTask', function ($query) use ($search) {
                    $query->where('task_name', 'like', "%{$search}%");
                });

                $q->orWhereHas('unit', function ($query) use ($search) {
                    $query->where('unit_name', 'like', "%{$search}%");
                });

            });

        }

        $tasks = $query
            ->orderBy('guest_checkout_date')
            ->paginate(10);

        return view('staff.tasks.index', compact('tasks', 'search'));
    }

    public function show(HousekeepingTask $task)
    {
        abort_if($task->assigned_staff_id != auth()->id(), 403);

        $task->load('unit', 'cleaningTask');

        return view('staff.tasks.show', compact('task'));
    }

    public function complete(Request $request, HousekeepingTask $task)
    {
        abort_if($task->assigned_staff_id != auth()->id(), 403);

        $request->validate([
            'image' => 'required|image|max:2048',
            'completion_notes' => 'nullable|string'
        ]);

        $image = $request->file('image')->store('task-completions', 'public');

        TaskCompletion::create([
    'housekeeping_task_id' => $task->id,
    'staff_id' => auth()->id(),
    'image_path' => $image,
    'completion_notes' => $request->completion_notes,

    'approval_status' => 'pending',
]);

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()
            ->route('staff.history')
            ->with('success', 'Task completed successfully!');
    }
}