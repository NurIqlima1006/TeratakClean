<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Unit</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('units.store') }}">
                    @csrf

                    <!-- Unit Name -->
                    <div class="mb-6">
                        <label for="unit_name" style="display: block; font-weight: 600; margin-bottom: 8px;">Unit Name <span style="color: red;">*</span></label>
                        <input 
                            type="text" 
                            id="unit_name" 
                            name="unit_name" 
                            value="{{ old('unit_name') }}"
                            placeholder="e.g., Masam Masam Manis"
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;"
                        >
                        @error('unit_name')
                            <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Size -->
                    <div class="mb-6">
                        <label for="unit_size" style="display: block; font-weight: 600; margin-bottom: 8px;">Unit Size <span style="color: red;">*</span></label>
                        <select 
                            id="unit_size" 
                            name="unit_size"
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;"
                        >
                            <option value="">-- Select Size --</option>
                            <option value="small" {{ old('unit_size') === 'small' ? 'selected' : '' }}>Small (2-3 rooms)</option>
                            <option value="medium" {{ old('unit_size') === 'medium' ? 'selected' : '' }}>Medium (4-5 rooms)</option>
                            <option value="large" {{ old('unit_size') === 'large' ? 'selected' : '' }}>Large (6+ rooms)</option>
                        </select>
                        @error('unit_size')
                            <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Staff Allowed -->
                    <div class="mb-6">
                        <label for="max_staff_allowed" style="display: block; font-weight: 600; margin-bottom: 8px;">Maximum Staff Allowed <span style="color: red;">*</span></label>
                        <input 
                            type="number" 
                            id="max_staff_allowed" 
                            name="max_staff_allowed" 
                            value="{{ old('max_staff_allowed') }}"
                            placeholder="e.g., 2, 3, 4"
                            min="1"
                            max="10"
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;"
                        >
                        @error('max_staff_allowed')
                            <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Small: 2, Medium: 3, Large: 4</p>
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" style="background-color: #10b981; color: white; padding: 10px 24px; border-radius: 4px; border: none; cursor: pointer; font-weight: bold; font-size: 16px;">
                            ✓ Create Unit
                        </button>
                        <a href="{{ route('units.index') }}" style="background-color: #6b7280; color: white; padding: 10px 24px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 16px; display: inline-block;">
                            ✕ Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>