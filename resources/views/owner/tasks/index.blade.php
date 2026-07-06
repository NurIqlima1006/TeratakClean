<x-app-layout>

    <div class="max-w-7xl mx-auto">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                Task Assignment
            </h1>

            <p class="text-gray-500 mt-2">
                Monitor all assigned housekeeping and maintenance tasks.
            </p>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Total Tasks</p>

                <h2 class="text-3xl font-bold mt-2">
                    {{ $tasks->count() }}
                </h2>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Pending</p>

                <h2 class="text-3xl font-bold text-yellow-600 mt-2">
                    {{ $tasks->where('status','pending')->count() }}
                </h2>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Completed</p>

                <h2 class="text-3xl font-bold text-green-600 mt-2">
                    {{ $tasks->where('status','completed')->count() }}
                </h2>
            </div>

        </div>

        <!-- Table -->

        <div class="bg-white rounded-lg shadow">

            <div class="p-6 border-b">
                <h2 class="text-xl font-bold">
                    All Tasks
                </h2>
            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-6 py-3 text-left">
                                Unit
                            </th>

                            <th class="px-6 py-3 text-left">
                                Task
                            </th>

                            <th class="px-6 py-3 text-left">
                                Assigned Staff
                            </th>

                            <th class="px-6 py-3 text-left">
                                Status
                            </th>

                            <th class="px-6 py-3 text-left">
                                Type
                            </th>

                            <th class="px-6 py-3 text-left">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($tasks as $task)

                        <tr class="border-b">

                            <td class="px-6 py-4">

                                {{ $task->unit->unit_name ?? '-' }}

                            </td>

                            <td class="px-6 py-4">

                                @if(get_class($task) == 'App\Models\HousekeepingTask')

                                    {{ $task->cleaningTask->task_name }}

                                @else

                                    {{ $task->task_name }}

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                {{ $task->assignedStaff->name ?? $task->assignedStaff->code ?? 'Not Assigned' }}

                            </td>

                            <td class="px-6 py-4">

                                @if($task->status == 'completed')

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                                        Completed

                                    </span>

                                @else

                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">

                                        Pending

                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                @if(get_class($task) == 'App\Models\HousekeepingTask')

                                    🧹 Housekeeping

                                @else

                                    🔧 Maintenance

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                @if($task->status == 'completed')

                                    <button
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm">
                                        👁 View
                                    </button>

                                @else

                                    <span class="text-gray-400 text-sm">
                                        No Evidence
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="text-center py-8 text-gray-500">

                                No tasks available.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>