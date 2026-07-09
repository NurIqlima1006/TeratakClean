<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task History') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

            <!-- Header -->
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h1 class="text-3xl font-bold">
                    Task History
                </h1>

                <p class="text-gray-500 mt-2">
                    All completed tasks assigned to you.
                </p>
            </div>

            <!-- Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-6 py-3 text-left">Date</th>

                            <th class="px-6 py-3 text-left">Unit</th>

                            <th class="px-6 py-3 text-left">Task</th>

                            <th class="px-6 py-3 text-left">Notes</th>

                            <th class="px-6 py-3 text-center">
                                Evidence
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        @forelse($history as $item)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4">
                                {{ $item->created_at->format('d M Y') }}
                                <br>

                                <span class="text-sm text-gray-500">
                                    {{ $item->created_at->format('h:i A') }}
                                </span>

                            </td>

                            <td class="px-6 py-4">
                                {{ $item->housekeepingTask->unit->unit_name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->housekeepingTask->cleaningTask->task_name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->completion_notes ?: '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">

                                @if($item->image_path)

                                    <a href="{{ asset('storage/'.$item->image_path) }}"
                                       target="_blank"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

                                        View

                                    </a>

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="5"
                                class="text-center py-8 text-gray-500">

                                No completed tasks found.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-6">
                {{ $history->links() }}
            </div>

        </div>
    </div>

</x-app-layout>