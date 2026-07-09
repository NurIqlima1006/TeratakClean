<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Greeting -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold text-gray-900">
                    Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening') }},
                    {{ auth()->user()->name }} 👋
                </h1>

                <div class="mt-4 flex gap-8">
                    <div class="text-gray-600">
                        <p class="text-sm font-semibold">{{ now()->format('l') }}</p>
                        <p class="text-lg font-bold">{{ now()->format('d F Y') }}</p>
                    </div>

                    <div class="text-gray-600">
                        <p class="text-sm font-semibold">Current Time</p>
                        <p class="text-lg font-bold" id="current-time">
                            {{ now()->format('h:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

                <!-- Pending -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-600 text-sm">Pending Tasks</p>
                            <p class="text-3xl font-bold">{{ $pendingTasks }}</p>
                        </div>

                        <i class="fas fa-list-check text-3xl text-yellow-500"></i>
                    </div>
                </div>

                <!-- Assigned Units -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-600 text-sm">Assigned Units</p>
                            <p class="text-3xl font-bold">{{ $assignedUnits }}</p>
                        </div>

                        <i class="fas fa-building text-3xl text-blue-500"></i>
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-600 text-sm">Completed Tasks</p>
                            <p class="text-3xl font-bold">{{ $completedTasks }}</p>
                        </div>

                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                    </div>
                </div>

                <!-- Maintenance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-600 text-sm">Maintenance Tasks</p>
                            <p class="text-3xl font-bold">{{ $maintenanceTasks }}</p>
                        </div>

                        <i class="fas fa-screwdriver-wrench text-3xl text-red-500"></i>
                    </div>
                </div>

            </div>

            <!-- My Assigned Tasks -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        My Assigned Tasks
                    </h3>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-100">

                            <tr>
                                <th class="px-4 py-3 text-left">Task</th>
                                <th class="px-4 py-3 text-left">Unit</th>
                                <th class="px-4 py-3 text-left">Check-out Date</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-center">Action</th>
                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-200">

                        @forelse($todayTasks as $task)

                            <tr>

                                <td class="px-4 py-3">
                                    {{ $task->cleaningTask->task_name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $task->unit->unit_name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $task->guest_checkout_date->format('d M Y') }}
                                </td>

                                <td class="px-4 py-3">

                                    @if($task->status == 'pending')

                                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm">
                                            Pending
                                        </span>

                                    @else

                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                                            Completed
                                        </span>

                                    @endif

                                </td>

                                <td class="px-4 py-3 text-center">

                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

                                        View

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center py-6 text-gray-500">

                                    No tasks assigned.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>

    <script>
        function updateTime() {
            const now = new Date();

            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            document.getElementById('current-time').textContent = timeString;
        }

        setInterval(updateTime, 1000);
    </script>

</x-app-layout>