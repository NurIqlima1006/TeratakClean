<x-app-layout>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

            <div class="flex justify-between items-center mb-6">

                <h2 class="text-2xl font-bold">
                    My Tasks
                </h2>

                <form method="GET">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search task or unit..."
                        class="border rounded-lg px-4 py-2">

                </form>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-4 py-3 text-left">Task</th>

                            <th class="px-4 py-3 text-left">Unit</th>

                            <th class="px-4 py-3 text-left">Check-out</th>

                            <th class="px-4 py-3 text-left">Status</th>

                            <th class="px-4 py-3 text-center">Action</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200">

                    @forelse($tasks as $task)

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

                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                        Pending
                                    </span>

                                @else

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                        Completed
                                    </span>

                                @endif

                            </td>

                            <td class="px-4 py-3 text-center">

                                @if($task->status == 'pending')

                                    <a href="{{ route('staff.tasks.show', $task) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-block">
                                        View Details
                                    </a>

                                @else

                                    <a href="{{ route('staff.tasks.show',$task) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

                                        View

                                    </a>

                                @endif

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

            <div class="mt-6">

                {{ $tasks->links() }}

            </div>

        </div>

    </div>
</div>

</x-app-layout>