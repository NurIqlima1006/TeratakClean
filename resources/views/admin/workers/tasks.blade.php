<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tasks.assignment') }}" style="color: #6f6e66; font-size: 20px; text-decoration: none;">← Back</a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    @if($user->role === 'staff')
                        🧹
                    @elseif($user->role === 'handyman')
                        🔧
                    @else
                        🌱
                    @endif
                    {{ $user->name ?? $user->code }}
                </h2>
                <p style="color: #6b7280; font-size: 14px;">{{ $user->code }} ({{ ucfirst($user->role) }})</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Card -->
            <div class="bg-white shadow sm:rounded-lg p-6 mb-6">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600;">PENDING TASKS</p>
                        <p style="font-size: 32px; font-weight: bold; color: #6f6e66;">{{ $tasks->count() }}</p>
                    </div>
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600;">CLEANING TASKS</p>
                        <p style="font-size: 32px; font-weight: bold; color: #0ea5e9;">
                            {{ $tasks->filter(function($t) { return get_class($t) === 'App\Models\HousekeepingTask'; })->count() }}
                        </p>
                    </div>
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600;">MAINTENANCE TASKS</p>
                        <p style="font-size: 32px; font-weight: bold; color: #f97316;">
                            {{ $tasks->filter(function($t) { return get_class($t) === 'App\Models\MaintenanceTask'; })->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    @if($tasks->count() > 0)
                        <h3 class="font-semibold text-lg mb-4">📋 Assigned Tasks</h3>
                        
                        <table class="w-full" style="border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #f3f4f6; border-bottom: 1px solid #e5e7eb;">
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Unit</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Task</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Type</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 12px;"><strong>{{ $task->unit->unit_name }}</strong></td>
                                        <td style="padding: 12px;">
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
                                        <td style="padding: 12px; font-size: 14px;">
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
                    @else
                        <p style="text-align: center; color: #6b7280; padding: 32px 0;">
                            ✓ No pending tasks!
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>