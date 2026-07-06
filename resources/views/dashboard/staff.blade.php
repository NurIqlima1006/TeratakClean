<?php
dd('staff blade loade');
?>
<x-app-layout>
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 32px;">
            <h1 style="font-size: 28px; font-weight: bold; color: #1f2937; margin: 0;">
                @if(auth()->user()->role === 'staff')
                    🧹 My Tasks
                @elseif(auth()->user()->role === 'handyman')
                    🔧 My Tasks
                @else
                    🌱 My Tasks
                @endif
            </h1>
            <p style="color: #6b7280; margin: 8px 0 0 0;">{{ auth()->user()->name ?? auth()->user()->code }}</p>
        </div>

        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #3b82f6;">
                <p style="color: #6b7280; font-size: 12px; font-weight: 600; margin: 0;">TOTAL PENDING</p>
                <p style="font-size: 32px; font-weight: bold; color: #3b82f6; margin: 8px 0 0 0;">
                    {{ $pendingCount ?? 0 }}
                </p>
            </div>
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">
                <p style="color: #6b7280; font-size: 12px; font-weight: 600; margin: 0;">COMPLETED TODAY</p>
                <p style="font-size: 32px; font-weight: bold; color: #10b981; margin: 8px 0 0 0;">
                    {{ $completedToday ?? 0 }}
                </p>
            </div>
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">
                <p style="color: #6b7280; font-size: 12px; font-weight: 600; margin: 0;">COMPLETION RATE</p>
                <p style="font-size: 32px; font-weight: bold; color: #f59e0b; margin: 8px 0 0 0;">
                    {{ $completionRate ?? 0 }}%
                </p>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h2 style="font-size: 20px; font-weight: bold; margin: 0 0 20px 0;">📋 My Pending Tasks</h2>

            @if($tasks && $tasks->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                                <th style="padding: 12px; text-align: left; font-weight: 600;">Unit</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600;">Task</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600;">Type</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600;">Date</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
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
                                    <td style="padding: 12px;">
                                        <button type="button" class="complete-btn" data-task-id="{{ $task->id }}" data-task-type="{{ get_class($task) }}" style="padding: 6px 12px; background-color: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600;">
                                            ✓ Complete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="text-align: center; color: #6b7280; padding: 32px 0;">
                    ✓ No pending tasks! Great job! 🎉
                </p>
            @endif
        </div>
    </div>

    <!-- Complete Task Modal -->
    <div id="completeModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 32px; border-radius: 8px; max-width: 500px; width: 90%;">
            <h2 style="font-size: 20px; font-weight: bold; margin: 0 0 16px 0;">📸 Complete Task</h2>
            <p style="color: #6b7280; margin: 0 0 20px 0;">Upload a photo as evidence of completion</p>

            <form id="completeForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="taskId" name="task_id">
                <input type="hidden" id="taskType" name="task_type">

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Photo Evidence</label>
                    <input type="file" name="image" accept="image/*" required style="width: 100%; padding: 10px; border: 2px dashed #d1d5db; border-radius: 6px;">
                    <p style="color: #6b7280; font-size: 12px; margin: 8px 0 0 0;">JPG, PNG, up to 5MB</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Completion Notes</label>
                    <textarea name="notes" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; min-height: 100px;" placeholder="Add any notes about task completion..."></textarea>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" style="flex: 1; padding: 12px; background-color: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        ✓ Submit
                    </button>
                    <button type="button" onclick="closeCompleteModal()" style="flex: 1; padding: 12px; background-color: #e5e7eb; color: #374151; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function closeCompleteModal() {
            document.getElementById('completeModal').style.display = 'none';
        }

        document.querySelectorAll('.complete-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const taskId = this.getAttribute('data-task-id');
                const taskType = this.getAttribute('data-task-type');
                
                document.getElementById('taskId').value = taskId;
                document.getElementById('taskType').value = taskType;
                document.getElementById('completeModal').style.display = 'flex';
            });
        });

        document.getElementById('completeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("tasks.complete") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Task completed! ✓');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        });
    </script>
</x-app-layout>