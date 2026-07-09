<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of all units.
     */
    public function index(Request $request)
    {
        // Get search query if provided
        $search = $request->input('search');
        
        // Build query
        $query = Unit::query();
        
        // Apply search filter
        if ($search) {
            $query->where('unit_name', 'like', '%' . $search . '%');
        }
        
        // Get all units with pagination
        $units = $query->paginate(10);
        
        return view('admin.units.index', compact('units', 'search'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create()
    {
        return view('admin.units.create');
    }

    /**
     * Store a newly created unit in database.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'unit_name' => 'required|string|max:255|unique:units,unit_name',
            'unit_size' => 'required|in:small,medium,large',
            'max_staff_allowed' => 'required|integer|min:1|max:10',
        ]);
        
        // Create unit
        Unit::create($validated);
        
        return redirect()->route('admin.units.index')
            ->with('success', 'Unit created successfully!');
    }

    /**
     * Show the form for editing a unit.
     */
    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    /**
     * Update unit in database.
     */
    public function update(Request $request, Unit $unit)
    {
        // Validate input
        $validated = $request->validate([
            'unit_name' => 'required|string|max:255|unique:units,unit_name,' . $unit->id,
            'unit_size' => 'required|in:small,medium,large',
            'max_staff_allowed' => 'required|integer|min:1|max:10',
        ]);
        
        // Update unit
        $unit->update($validated);
        
        return redirect()->route('admin.units.index')
            ->with('success', 'Unit updated successfully!');
    }

    /**
     * Delete unit from database.
     */
    public function destroy(Unit $unit)
    {
        // Delete unit
        $unit->delete();
        
        return redirect()->route('admin.units.index')
            ->with('success', 'Unit deleted successfully!');
    }
    /**
 * Display units for owner (view only).
 */
public function ownerIndex(Request $request)
{
    $search = $request->input('search');

    $query = Unit::query();

    if ($search) {
        $query->where('unit_name', 'like', '%' . $search . '%');
    }

    $units = $query->paginate(10);

    return view('owner.units.index', compact('units', 'search'));
}
}