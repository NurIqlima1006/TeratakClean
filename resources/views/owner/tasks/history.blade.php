<x-app-layout>

<div class="max-w-7xl mx-auto px-6 py-6">

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-800">
            Task History
        </h1>

        <p class="text-gray-500 mt-2">
            View all approved housekeeping and maintenance tasks.
        </p>

    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500">
                    Approved Tasks
                </p>

                <h2 class="text-4xl font-bold mt-2">

                    {{ $completions->count() }}

                </h2>

            </div>

            <div class="text-5xl">
                📜
            </div>

        </div>

    </div>

    @if($completions->count())

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        @foreach($completions as $completion)

        <div class="bg-white rounded-xl shadow border overflow-hidden">

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

                    </p>

                </div>

                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">

                    Approved

                </span>

            </div>

            <div class="p-6">

                <p class="text-gray-500 text-sm">
                    🏡 Unit
                </p>

                <p class="font-semibold text-lg mb-4">

                    @if($completion->housekeepingTask)

                        {{ $completion->housekeepingTask->unit->unit_name }}

                    @else

                        {{ $completion->maintenanceTask->unit->unit_name }}

                    @endif

                </p>

                <p class="text-gray-500 text-sm">
                    📋 Task
                </p>

                <p class="font-semibold mb-4">

                    @if($completion->housekeepingTask)

                        {{ $completion->housekeepingTask->cleaningTask->task_name }}

                    @else

                        {{ $completion->maintenanceTask->task_name }}

                    @endif

                </p>

                <p class="text-gray-500 text-sm">
                    📝 Notes
                </p>

                <div class="bg-gray-50 rounded-lg p-3 mb-5">

                    {{ $completion->completion_notes ?? 'No notes provided.' }}

                </div>

                <img
                    src="{{ asset('storage/'.$completion->image_path) }}"
                    class="w-full h-64 object-cover rounded-lg border mb-5">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-gray-500 text-sm">
                            Approved
                        </p>

                        <p class="font-semibold">

                            {{ $completion->updated_at->format('d M Y h:i A') }}

                        </p>

                    </div>

                    <a
                        href="{{ asset('storage/'.$completion->image_path) }}"
                        target="_blank"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

                        👁 View Image

                    </a>

                </div>

            </div>

        </div>

        @endforeach

    </div>

    @else

    <div class="bg-white rounded-xl shadow-sm border p-10 text-center">

        <div class="text-6xl mb-4">
            📭
        </div>

        <h2 class="text-2xl font-bold">

            No Approved Tasks Yet

        </h2>

        <p class="text-gray-500 mt-2">

            Approved tasks will appear here.

        </p>

    </div>

    @endif

</div>

</x-app-layout>