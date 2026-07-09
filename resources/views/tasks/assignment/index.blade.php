<x-app-layout>
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header with Buttons -->
        <div style="display: flex; gap: 12px; margin-bottom: 32px; flex-wrap: wrap;">
    <a href="{{ route('tasks.generate-rotation') }}" style="padding: 12px 24px; background-color: #06b6d4; color: white; border-radius: 8px; text-decoration: none; font-weight: bold; border: none; cursor: pointer;">
        🔄 Generate Rotation
    </a>

    @if($pendingTasks->count() > 0)
        <form method="POST" action="{{ route('tasks.auto-assign') }}" style="display: inline;">
            @csrf
            <button type="submit" style="padding: 12px 24px; background-color: #8b5cf6; color: white; border-radius: 8px; border: none; cursor: pointer; font-weight: bold;" onclick="return confirm('Auto-assign {{ $pendingTasks->count() }} pending tasks?')">
                🤖 Auto-Assign All
            </button>
        </form>
    @endif

    <a href="{{ route('tasks.export-pdf') }}" style="padding: 12px 24px; background-color: #ef4444; color: white; border-radius: 8px; text-decoration: none; font-weight: bold; border: none;">
        📄 Export PDF
    </a>

    <a href="{{ route('tasks.approval') }}"
       style="padding: 12px 24px; background-color: #10b981; color: white; border-radius: 8px; text-decoration: none; font-weight: bold; border: none;">

        ✅ Task Approval

        @if($pendingApprovals > 0)
            ({{ $pendingApprovals }})
        @endif

    </a>

    <a href="{{ route('tasks.history') }}"
   style="padding: 12px 24px; background-color: #6366f1; color: white; border-radius: 8px; text-decoration: none; font-weight: bold;">

    📜 Task History

</a>

</div>

        <!-- Status Messages -->
        @if (session('success'))
            <div style="margin-bottom: 16px; padding: 16px; background-color: #dcfce7; color: #166534; border-radius: 8px; border-left: 4px solid #22c55e;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div style="margin-bottom: 16px; padding: 16px; background-color: #fef3c7; color: #92400e; border-radius: 8px; border-left: 4px solid #f59e0b;">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('info'))
            <div style="margin-bottom: 16px; padding: 16px; background-color: #dbeafe; color: #1e40af; border-radius: 8px; border-left: 4px solid #6b7280;">
                {{ session('info') }}
            </div>
        @endif

        <!-- Quick Add Urgent Maintenance Task -->
        <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 24px;">
            <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 16px;">➕ Add Urgent Maintenance Task</h3>
            <form method="POST" action="{{ route('maintenance.quick-create') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: end;">
                @csrf
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px; color: #374151;">Unit</label>
                    <select name="unit_id" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                        <option value="">Select Unit</option>
                        @foreach(\App\Models\Unit::all() as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px; color: #374151;">Task Name</label>
                    <input type="text" name="task_name" placeholder="e.g., Fix broken door" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;" required>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px; color: #374151;">Description</label>
                    <input type="text" name="description" placeholder="Details..." style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px; color: #374151;">Assign To</label>
                    <select name="assigned_staff_id" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">

    <option value="">Auto-assign</option>

    <option value="housekeeping">
        Housekeeping Staff (Auto)
    </option>

    <optgroup label="Handyman">
        @foreach(\App\Models\User::where('role','handyman')->get() as $worker)
            <option value="{{ $worker->id }}">
                {{ $worker->code }}
            </option>
        @endforeach
    </optgroup>

    <optgroup label="Gardener">
        @foreach(\App\Models\User::where('role','gardener')->get() as $worker)
            <option value="{{ $worker->id }}">
                {{ $worker->code }}
            </option>
        @endforeach
    </optgroup>

</select>
                </div>
                <button type="submit" style="padding: 10px 20px; background-color: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 14px;">
                    Add Task
                </button>
            </form>
        </div>

        <!-- Staff Workload Summary -->
        <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 24px;">
            <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 20px;">📊 Current Worker Workload</h3>
            
            @if($staff->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
                    @foreach($staff as $member)
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; background-color: #f9fafb; cursor: pointer; transition: all 0.3s;" data-href="{{ route('workers.tasks', $member->id) }}" onmouseover="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'" onclick="window.location = this.getAttribute('data-href')">
                            <p style="font-weight: 600; font-size: 16px; margin: 0 0 4px 0; color: #1f2937;">
                                @if($member->role === 'staff')
                                    🧹
                                @elseif($member->role === 'handyman')
                                    🔧
                                @else
                                    🌱
                                @endif
                                {{ $member->name ?? $member->code }}
                            </p>
                            <p style="color: #6b7280; font-size: 13px; margin: 0 0 12px 0;">{{ $member->code }}</p>
                            <div>
                                <p style="font-size: 28px; font-weight: bold; color: #6b7280; margin: 0;">{{ $staffWorkload[$member->id]['pending_tasks'] }}</p>
                                <p style="color: #6b7280; font-size: 12px; margin: 4px 0 0 0;">pending tasks</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #6b7280; text-align: center; padding: 32px 0;">No workers available</p>
            @endif
        </div>

        <!-- Pending Tasks Table -->
        <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 16px;">
                📋 All Pending Tasks ({{ $pendingTasks->count() }})
            </h3>

            @if($pendingTasks->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">Unit</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">Task</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">Type</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingTasks as $task)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 12px; color: #1f2937;"><strong>{{ $task->unit->unit_name }}</strong></td>
                                    <td style="padding: 12px; color: #374151;">
                                        @if(get_class($task) === 'App\Models\HousekeepingTask')
                                            {{ $task->cleaningTask->task_name }}
                                        @else
                                            {{ $task->task_name }}
                                        @endif
                                    </td>
                                    <td style="padding: 12px;">
                                        @if(get_class($task) === 'App\Models\HousekeepingTask')
                                            <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">🧹 Cleaning</span>
                                        @else
                                            <span style="background-color: #fce7f3; color: #be185d; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">🔧 Maintenance</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; font-size: 13px; color: #6b7280;">
                                        @if(get_class($task) === 'App\Models\HousekeepingTask')
                                            {{ \Carbon\Carbon::parse($task->guest_checkout_date)->format('d M Y') }}
                                        @else
                                            Today
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="text-align: center; color: #6b7280; padding: 32px 0;">
                    ✓ All tasks assigned! 
                    <br><br>
                    <a href="{{ route('housekeeping.checkouts') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">Add checkouts →</a>
                </p>
            @endif
        </div>
    </div>
</x-app-layout>