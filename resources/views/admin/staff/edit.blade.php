<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Worker
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('staff.update', $staff) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Worker Code
                            </label>

                            <input
                                type="text"
                                value="{{ $staff->code }}"
                                class="w-full border rounded-lg px-4 py-2 bg-gray-100"
                                readonly>

                        </div>

                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Role
                            </label>

                            <input
                                type="text"
                                value="{{ ucfirst($staff->role) }}"
                                class="w-full border rounded-lg px-4 py-2 bg-gray-100"
                                readonly>

                        </div>

                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $staff->name) }}"
                                class="w-full border rounded-lg px-4 py-2">

                        </div>

                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $staff->phone) }}"
                                class="w-full border rounded-lg px-4 py-2">

                        </div>

                        <div class="md:col-span-2">

                            <label class="block text-sm font-medium mb-2">
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="w-full border rounded-lg px-4 py-2">

                            <p class="text-sm text-gray-500 mt-2">
                                Leave this field empty if you don't want to change the password.
                            </p>

                        </div>

                    </div>

                    <div class="mt-8 flex justify-end gap-3">

                        <a href="{{ route('staff.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                            Cancel

                        </a>

                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

                            Update Worker

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>