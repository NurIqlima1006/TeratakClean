<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Worker
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('staff.store') }}" method="POST">

                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Worker Role
                            </label>

                            <select
                                name="role"
                                class="w-full border rounded-lg px-4 py-2"
                                required>

                                <option value="">Select Role</option>
                                <option value="staff">Staff</option>
                                <option value="handyman">Handyman</option>
                                <option value="gardener">Gardener</option>

                            </select>

                        </div>

                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Worker Code
                            </label>

                            <input
                                type="text"
                                name="code"
                                value="{{ old('code', $nextCode) }}"
                                class="w-full border rounded-lg px-4 py-2"
                                readonly>

                        </div>

                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="w-full border rounded-lg px-4 py-2">

                        </div>

                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="w-full border rounded-lg px-4 py-2">

                        </div>

                        <div class="md:col-span-2">

                            <label class="block text-sm font-medium mb-2">
                                Default Password
                            </label>

                            <input
                                type="text"
                                name="password"
                                value="password123"
                                class="w-full border rounded-lg px-4 py-2"
                                readonly>

                            <p class="text-sm text-gray-500 mt-2">
                                Worker can change their password after logging in.
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
                            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg">

                            Create Worker

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>