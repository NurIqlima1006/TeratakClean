<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Record Guest Checkout</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('housekeeping.checkouts.store') }}">
                    @csrf

                    <!-- Unit -->
                    <div class="mb-6">
                        <label for="unit_id" style="display: block; font-weight: 600; margin-bottom: 8px;">Unit <span style="color: red;">*</span></label>
                        <select 
                            id="unit_id" 
                            name="unit_id"
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;"
                        >
                            <option value="">-- Select Unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') === (string)$unit->id ? 'selected' : '' }}>
                                    {{ $unit->unit_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Checkout Date -->
                    <div class="mb-6">
                        <label for="checkout_date" style="display: block; font-weight: 600; margin-bottom: 8px;">Checkout Date <span style="color: red;">*</span></label>
                        <input 
                            type="date" 
                            id="checkout_date" 
                            name="checkout_date" 
                            value="{{ old('checkout_date', today()->toDateString()) }}"
                            min="{{ today()->toDateString() }}"
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;"
                        >
                        @error('checkout_date')
                            <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" style="background-color: #10b981; color: white; padding: 10px 24px; border-radius: 4px; border: none; cursor: pointer; font-weight: bold; font-size: 16px;">
                            ✓ Record Checkout
                        </button>
                        <a href="{{ route('housekeeping.checkouts') }}" style="background-color: #6b7280; color: white; padding: 10px 24px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 16px; display: inline-block;">
                            ✕ Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>