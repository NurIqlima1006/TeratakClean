<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Housekeeping Schedule</h2>
            <a href="{{ route('housekeeping.checkouts.create') }}" style="display: inline-block; background-color: #16a34a; color: white; padding: 8px 16px; border-radius: 4px; font-weight: bold; text-decoration: none; font-size: 16px;">
                + Add Checkout
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            @if (session('warning'))
                <div class="mb-4 p-4 bg-yellow-100 text-yellow-700 rounded">{{ session('warning') }}</div>
            @endif

            <!-- Date Filter & Assign Tasks -->
            <div class="bg-white shadow sm:rounded-lg p-6 mb-6">
                <div class="flex gap-4 items-center justify-between">
                    <div class="flex gap-4 items-center">
                        <label class="font-semibold">Select Date:</label>
                        <form method="GET" class="flex gap-2">
                            <input type="date" name="date" value="{{ $date }}" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            <button type="submit" style="padding: 8px 16px; background-color: #3b82f6; color: white; border-radius: 4px; border: none; cursor: pointer;">Filter</button>
                        </form>
                    </div>
                    
                    @if($unitsWithCheckouts->count() > 0)
                        <form method="POST" action="{{ route('housekeeping.assign-tasks') }}" style="display: inline;">
                            @csrf
                            <input type="hidden" name="date" value="{{ $date }}">
                            <button type="submit" style="padding: 10px 24px; background-color: #f59e0b; color: white; border-radius: 4px; border: none; cursor: pointer; font-weight: bold; font-size: 16px;" onclick="return confirm('Generate tasks for {{ $unitsWithCheckouts->count() }} unit(s)?')">
                                🔄 Assign Tasks for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Checkouts Table -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    @if ($checkouts->count() > 0)
                        <h3 class="font-semibold text-lg mb-4">Checkouts for {{ \Carbon\Carbon::parse($date)->format('d M Y') }} ({{ $checkouts->count() }} units)</h3>
                        
                        <table class="w-full" style="border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #f3f4f6; border-bottom: 1px solid #e5e7eb;">
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Unit Name</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Size</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Checkout Date</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600;">Status</th>
                                    <th style="padding: 12px; text-align: center; font-weight: 600;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($checkouts as $checkout)
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 12px;"><strong>{{ $checkout->unit->unit_name }}</strong></td>
                                        <td style="padding: 12px;">
                                            @if($checkout->unit->unit_size === 'small')
                                                <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Small</span>
                                            @elseif($checkout->unit->unit_size === 'medium')
                                                <span style="background-color: #fef08a; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Medium</span>
                                            @else
                                                <span style="background-color: #fecaca; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Large</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px;">{{ \Carbon\Carbon::parse($checkout->checkout_date)->format('d M Y') }}</td>
                                        <td style="padding: 12px;">
                                            @if($checkout->is_processed)
                                                <span style="background-color: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">✓ Processed</span>
                                            @else
                                                <span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">⏳ Pending</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px; text-align: center;">
                                            <form action="{{ route('housekeeping.checkouts.destroy', $checkout->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background-color: #ef4444; color: white; padding: 6px 12px; border-radius: 4px; border: none; cursor: pointer; font-size: 14px;" onclick="return confirm('Delete this checkout?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p style="text-align: center; color: #6b7280; padding: 32px 0;">
                            No checkouts recorded for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                            <br><br>
                            <a href="{{ route('housekeeping.checkouts.create') }}" style="color: #3b82f6; text-decoration: underline;">Add a checkout →</a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded" style="border-left: 4px solid #3b82f6;">
                <p style="color: #1e40af; font-weight: 600; margin-bottom: 8px;">💡 How it works:</p>
                <ol style="color: #1e40af; margin-left: 20px;">
                    <li>Add guest checkouts for units</li>
                    <li>Select a date and click "Assign Tasks"</li>
                    <li>System generates 9 cleaning tasks per unit</li>
                    <li>Tasks appear as "pending" and ready for auto-assignment</li>
                </ol>
            </div>
        </div>
    </div>
</x-app-layout>