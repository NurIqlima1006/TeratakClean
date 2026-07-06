<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Maintenance Tasks</h2>
            <a href="{{ route('maintenance.create') }}" style="display: inline-block; background-color: #16a34a; color: white; padding: 8px 16px; border-radius: 4px; font-weight: bold; text-decoration: none; font-size: 16px;">
                + Add Task
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <!-- Search -->
                <div class="p-6 border-b">
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search tasks..." class="flex-1 px-3 py-2 border rounded">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Search</button>
                        @if($search)<a href="{{ route('maintenance.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded">Clear</a>@endif
                    </form>
                </div>

                <!-- Table -->
                <div class="p-6">
                    @if ($maintenanceTasks->count() > 0)
                        <table class="w-full" style="border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #f3f4f6; border-bottom: 1px solid #e5e7eb;">
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Task Name</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Unit</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Frequency</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Assigned To</th>
                                    <th style="padding: 12px; text-align: center; font-weight: 600;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($maintenanceTasks as $task)
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 12px;"><strong>{{ $task->task_name }}</strong></td>
                                        <td style="padding: 12px;">{{ $task->unit->unit_name }}</td>
                                        <td style="padding: 12px;">
                                            @if($task->status === 'pending')
                                                <span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">⏳ Pending</span>
                                            @else
                                                <span style="background-color: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">✓ Completed</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px;">
                                            @if($task->assigned_staff_id)
                                                @php
                                                    $worker = \App\Models\User::find($task->assigned_staff_id);
                                                @endphp
                                                @if($worker->role === 'handyman')
                                                    <span style="background-color: #fce7f3; color: #be185d; padding: 4px 8px; border-radius: 4px; font-size: 12px;">🔧 {{ $worker->code }}</span>
                                                @elseif($worker->role === 'gardener')
                                                    <span style="background-color: #f0fdf4; color: #15803d; padding: 4px 8px; border-radius: 4px; font-size: 12px;">🌱 {{ $worker->code }}</span>
                                                @endif
                                            @else
                                                <span style="color: #6b7280; font-size: 12px;">Unassigned</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px; text-align: center;">
                                            <a href="{{ route('maintenance.edit', $task->id) }}" style="background-color: #6b7280; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px; margin-right: 8px;">Edit</a>
                                            <form action="{{ route('maintenance.destroy', $task->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background-color: #ef4444; color: white; padding: 6px 12px; border-radius: 4px; border: none; cursor: pointer; font-size: 14px;" onclick="return confirm('Delete this task?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div style="margin-top: 24px;">
                            {{ $maintenanceTasks->links() }}
                        </div>
                    @else
                        <p style="text-align: center; color: #6b7280; padding: 32px 0;">No maintenance tasks found</p>
                    @endif
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded" style="border-left: 4px solid #6b7280;">
                <p style="color: #1e40af; font-weight: 600; margin-bottom: 8px;">💡 How it works:</p>
                <ol style="color: #1e40af; margin-left: 20px;">
                    <li>Create a maintenance task template (e.g., "Fix roof gutters")</li>
                    <li>Set frequency (every 14 days)</li>
                    <li>Assign to Handyman or Gardener</li>
                    <li>System auto-generates instances every 14 days</li>
                    <li>Tasks appear in Tasks assignment page</li>
                </ol>
            </div>
        </div>
    </div>
</x-app-layout>