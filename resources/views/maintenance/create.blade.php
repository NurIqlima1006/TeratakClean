<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('maintenance.index') }}" style="color: #3b82f6; font-size: 20px; text-decoration: none;">← Back</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Maintenance Task</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('maintenance.store') }}">
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

                    <!-- Task Name -->
                    <div class="mb-6">
                        <label for="task_name" style="display: block; font-weight: 600; margin-bottom: 8px;">Task Name <span style="color: red;">*</span></label>
                        <input 
                            type="text" 
                            id="task_name" 
                            name="task_name" 
                            value="{{ old('task_name') }}"
                            placeholder="e.g., Fix toilet lamp, Paint walls, Trim garden"
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;"
                        >
                        @error('task_name')
                            <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" style="display: block; font-weight: 600; margin-bottom: 8px;">Description</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            placeholder="Additional notes..."
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; min-height: 100px;"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assigned Worker -->
                    <div class="mb-6">
                        <label for="assigned_staff_id" style="display: block; font-weight: 600; margin-bottom: 8px;">Assign To (Optional)</label>
                        <select 
                            id="assigned_staff_id" 
                            name="assigned_staff_id"
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;"
                        >
                            <option value="">-- Unassigned --</option>
                            @foreach($workers as $worker)
                                <option value="{{ $worker->id }}" {{ old('assigned_staff_id') === (string)$worker->id ? 'selected' : '' }}>
                                    @if($worker->role === 'handyman')
                                        🔧 {{ $worker->code }} (Handyman)
                                    @else
                                        🌱 {{ $worker->code }} (Gardener)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_staff_id')
                            <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" style="background-color: #10b981; color: white; padding: 10px 24px; border-radius: 4px; border: none; cursor: pointer; font-weight: bold; font-size: 16px;">
                            ✓ Create Task
                        </button>
                        <a href="{{ route('maintenance.index') }}" style="background-color: #6b7280; color: white; padding: 10px 24px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 16px; display: inline-block;">
                            ✕ Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>