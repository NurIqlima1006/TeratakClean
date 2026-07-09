<x-app-layout>
    

<div class="max-w-7xl mx-auto px-6 py-6">

    <!-- Header -->
    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-800">
            Task Approval
        </h1>

        <p class="text-gray-500 mt-2">
            Review completed housekeeping and maintenance tasks submitted by staff.
        </p>

    </div>

    <!-- Summary -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500">
                    Waiting For Approval
                </p>

                <h2 class="text-4xl font-bold mt-2">
                    {{ $completions->count() }}
                </h2>

            </div>

            <div class="text-5xl">
                ⏳
            </div>

        </div>

    </div>

    @if($completions->count())

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        @foreach($completions as $completion)

        <div class="bg-white rounded-xl shadow border overflow-hidden">

            <!-- Card Header -->
            <div class="border-b px-6 py-4 flex justify-between items-center">

                <div>

                    <h2 class="text-lg font-bold">

                        @if($completion->staff->role == 'staff')
                            🧹
                        @elseif($completion->staff->role == 'handyman')
                            🔧
                        @else
                            🌱
                        @endif

                        {{ $completion->staff->name ?? $completion->staff->code }}

                    </h2>

                    <p class="text-sm text-gray-500">

                        {{ strtoupper($completion->staff->code) }}

                        ({{ ucfirst($completion->staff->role) }})

                    </p>

                </div>

                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">

                    Pending

                </span>

            </div>

            <!-- Card Body -->
            <div class="p-6">

                <!-- Unit -->
                <div class="mb-4">

                    <p class="text-gray-500 text-sm">
                        🏡 Unit
                    </p>

                    <p class="font-semibold text-lg">

                        @if($completion->housekeepingTask)

                            {{ $completion->housekeepingTask->unit->unit_name }}

                        @else

                            {{ $completion->maintenanceTask->unit->unit_name }}

                        @endif

                    </p>

                </div>

                <!-- Task -->

                <div class="mb-4">

                    <p class="text-gray-500 text-sm">

                        📋 Task

                    </p>

                    <p class="font-semibold">

                        @if($completion->housekeepingTask)

                            {{ $completion->housekeepingTask->cleaningTask->task_name }}

                        @else

                            {{ $completion->maintenanceTask->task_name }}

                        @endif

                    </p>

                </div>

                <!-- Notes -->

                <div class="mb-5">

                    <p class="text-gray-500 text-sm">

                        📝 Notes

                    </p>

                    <div class="bg-gray-50 rounded-lg p-3">

                        {{ $completion->completion_notes ?? 'No notes provided.' }}

                    </div>

                </div>

                <!-- Evidence -->

                <div class="mb-5">

                    <p class="text-gray-500 text-sm mb-2">
                        📷 Evidence
                    </p>

                    <a href="{{ asset('storage/'.$completion->image_path) }}" target="_blank">

                        <img
                            src="{{ asset('storage/'.$completion->image_path) }}"
                            class="w-full h-64 object-cover rounded-lg border hover:opacity-90 transition">

                    </a>

                </div>

                <!-- Completion Time -->

                <div class="mb-6">

                    <p class="text-gray-500 text-sm">
                        📅 Completed
                    </p>

                    <p class="font-medium">

                        {{ $completion->created_at->format('d M Y') }}

                        •

                        {{ $completion->created_at->format('h:i A') }}

                    </p>

                </div>

                <!-- Buttons -->

                <div class="flex gap-3">

                    <a href="{{ asset('storage/'.$completion->image_path) }}"
                       target="_blank"
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg font-semibold transition">

                        👁 View Image

                    </a>

                    <form action="{{ route('tasks.approve', $completion->id) }}"
                          method="POST"
                          class="flex-1">

                        @csrf

                        <button
                            type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition">

                            ✅ Approve

                        </button>

                    </form>

                </div>

            </div>

        </div>

        @endforeach

    </div>

    @else

        <div class="bg-white rounded-xl shadow-sm border p-10 text-center">

            <div class="text-6xl mb-4">
                🎉
            </div>

            <h2 class="text-2xl font-bold text-gray-700 mb-2">
                No Tasks Waiting for Approval
            </h2>

            <p class="text-gray-500">
                All submitted tasks have been reviewed.
            </p>

        </div>

    @endif

</div>

</x-app-layout>