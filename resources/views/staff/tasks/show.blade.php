<x-app-layout>

    <div class="py-12">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h2 class="text-2xl font-bold mb-6">
                    Task Details
                </h2>

                <div class="grid grid-cols-2 gap-6">

                    <div>
                        <label class="text-gray-500 text-sm">Task</label>

                        <p class="font-semibold text-lg">
                            {{ $task->cleaningTask->task_name }}
                        </p>
                    </div>

                    <div>
                        <label class="text-gray-500 text-sm">Unit</label>

                        <p class="font-semibold text-lg">
                            {{ $task->unit->unit_name }}
                        </p>
                    </div>

                    <div>
                        <label class="text-gray-500 text-sm">Guest Check-out</label>

                        <p class="font-semibold text-lg">
                            {{ $task->guest_checkout_date->format('d F Y') }}
                        </p>
                    </div>

                    <div>
                        <label class="text-gray-500 text-sm">Status</label>

                        @if($task->status == 'pending')

                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">
                                Pending
                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                                Completed
                            </span>

                        @endif

                    </div>

                </div>

                <hr class="my-8">

                @if($task->status == 'pending')

                <form
                    method="POST"
                    action="{{ route('staff.tasks.complete',$task) }}"
                    enctype="multipart/form-data">

                    @csrf

                    <div class="mb-6">

                        <label class="block mb-2 font-semibold">

                            Upload Completion Photo

                        </label>

                        <input
                            type="file"
                            name="image"
                            class="w-full border rounded p-2"
                            required>

                    </div>

                    <div class="mb-6">

                        <label class="block mb-2 font-semibold">

                            Completion Notes

                        </label>

                        <textarea
                            name="completion_notes"
                            rows="4"
                            class="w-full border rounded p-3"
                            placeholder="Optional..."></textarea>

                    </div>

                    <button
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded">

                        Complete Task

                    </button>

                </form>

                @else

                    <div class="bg-green-50 border border-green-200 rounded-lg p-5">

                        <h3 class="font-bold text-green-700 mb-2">

                            Task Completed

                        </h3>

                        <p class="text-gray-700">

                            This task has already been completed.

                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>