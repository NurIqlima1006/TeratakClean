<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Units</h2>
            <a href="{{ route('units.create') }}" style="display: inline-block; background-color: #16a34a; color: white; padding: 8px 16px; border-radius: 4px; font-weight: bold; text-decoration: none; font-size: 16px;">
                + Add New Unit
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <!-- Search -->
                <div class="p-6 border-b">
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search by unit name..." class="flex-1 px-3 py-2 border rounded">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Search</button>
                        @if($search)<a href="{{ route('units.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded">Clear</a>@endif
                    </form>
                </div>

                <!-- Table -->
                <div class="p-6">
                    @if ($units->count() > 0)
                        <table class="w-full" style="border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #f3f4f6; border-bottom: 1px solid #e5e7eb;">
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Unit Name</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Size</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Max Staff</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Created</th>
                                    <th style="padding: 12px; text-align: center; font-weight: 600;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $unit)
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 12px;"><strong>{{ $unit->unit_name }}</strong></td>
                                        <td style="padding: 12px;">
                                            @if($unit->unit_size === 'small')
                                                <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Small</span>
                                            @elseif($unit->unit_size === 'medium')
                                                <span style="background-color: #fef08a; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Medium</span>
                                            @else
                                                <span style="background-color: #fecaca; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Large</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px;">{{ $unit->max_staff_allowed }} staff</td>
                                        <td style="padding: 12px; font-size: 14px;">{{ $unit->created_at->format('d M Y') }}</td>
                                        <td style="padding: 12px; text-align: center;">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div style="margin-top: 24px;">
                            {{ $units->links() }}
                        </div>
                    @else
                        <p style="text-align: center; color: #6b7280; padding: 32px 0;">No units found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>