<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Staff Management
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-5 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">

                    <form action="{{ route('staff.index') }}" method="GET" class="flex gap-2">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by code, name or phone..."
                            class="border rounded-lg px-4 py-2 w-80">

                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 rounded-lg">

                            Search

                        </button>

                    </form>

                    <div class="flex gap-3">

                        <a href="{{ route('staff.export-pdf') }}"
                           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">

                            <i class="fas fa-file-pdf"></i>
                            Export PDF

                        </a>

                        <a href="{{ route('staff.create') }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">

                            <i class="fas fa-plus"></i>
                            Add Worker

                        </a>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full border border-gray-200">

                        <thead class="bg-gray-100">

                        <tr>

                            <th class="px-4 py-3 border">Code</th>

                            <th class="px-4 py-3 border">Name</th>

                            <th class="px-4 py-3 border">Role</th>

                            <th class="px-4 py-3 border">Phone</th>

                            <th class="px-4 py-3 border">Pending Tasks</th>

                            <th class="px-4 py-3 border text-center">
                                Action
                            </th>

                        </tr>

                        </thead>

                        <tbody>
                            @forelse($staff as $member)

<tr>

    <td class="border px-4 py-3">
        {{ strtoupper($member->code) }}
    </td>

    <td class="border px-4 py-3">
        {{ $member->name ?? '-' }}
    </td>

    <td class="border px-4 py-3">

        @if($member->role == 'staff')

            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm">
                Staff
            </span>

        @elseif($member->role == 'handyman')

            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm">
                Handyman
            </span>

        @else

            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                Gardener
            </span>

        @endif

    </td>

    <td class="border px-4 py-3">
        {{ $member->phone ?? '-' }}
    </td>

    <td class="border px-4 py-3 text-center">
        {{ $member->pending_tasks }}
    </td>

    <td class="border px-4 py-3">

        <div class="flex justify-center gap-2">

            <a href="{{ route('staff.edit', $member) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded">

                <i class="fas fa-pen"></i>

            </a>

            <form action="{{ route('staff.destroy', $member) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this worker?')">

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded">

                    <i class="fas fa-trash"></i>

                </button>

            </form>

        </div>

    </td>

</tr>

@empty

<tr>

    <td colspan="6" class="text-center py-8 text-gray-500">

        No workers found.

    </td>

</tr>

@endforelse

</tbody>

</table>

</div>

<div class="mt-6">

    {{ $staff->links() }}

</div>

</div>

</div>

</div>

</x-app-layout>