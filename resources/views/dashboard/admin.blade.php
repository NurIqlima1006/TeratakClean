<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Greeting Section -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold text-gray-900">
                    Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening') }}, Admin 👋
                </h1>
                <div class="mt-4 flex gap-8">
                    <div class="text-gray-600">
                        <p class="text-sm font-semibold">{{ now()->format('l') }}</p>
                        <p class="text-lg font-bold">{{ now()->format('d F Y') }}</p>
                    </div>
                    <div class="text-gray-600">
                        <p class="text-sm font-semibold">Current Time</p>
                        <p class="text-lg font-bold" id="current-time">{{ now()->format('h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Today's Check-outs -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Today's Check-outs</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $todayCheckouts }}</p>
                        </div>
                        <i class="fas fa-door-open text-3xl text-blue-500"></i>
                    </div>
                </div>

                <!-- Pending Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Pending Tasks</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $pendingTasks }}</p>
                        </div>
                        <i class="fas fa-tasks text-3xl text-yellow-500"></i>
                    </div>
                </div>

                <!-- Completed Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Completed Today</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $completedToday }}</p>
                        </div>
                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                    </div>
                </div>

                <!-- Available Staff -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Available Staff</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $availableStaff }}</p>
                        </div>
                        <i class="fas fa-users text-3xl text-purple-500"></i>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Welcome to TeratakClean Admin Panel</h3>
                <p class="text-gray-600">This is your admin dashboard. More features coming soon!</p>
            </div>
        </div>
    </div>

    <script>
        // Update time every second
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
            document.getElementById('current-time').textContent = timeString;
        }
        setInterval(updateTime, 1000);
    </script>
</x-app-layout>