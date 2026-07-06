<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\HousekeepingTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class StaffController extends Controller
{
    /**
     * Display a listing of all staff.
     */
    public function index(Request $request)
    {
        // Get search query if provided
        $search = $request->input('search');
        
        // Build query
        $query = User::whereIn('role', ['staff', 'handyman', 'gardener']);
        
        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
        
        // Paginate results (10 per page)
        $staff = $query->paginate(10);
        
        // Add workload count for each staff
        $staff->getCollection()->transform(function ($member) {
            $member->pending_tasks = HousekeepingTask::where('assigned_staff_id', $member->id)
                ->where('status', 'pending')
                ->count();
            return $member;
        });
        
        return view('staff.index', compact('staff', 'search'));
    }

    /**
     * Show the form for creating a new staff.
     */
    public function create()
    {
        // Get next available staff code
        $lastStaff = User::where('role', 'staff')
            ->orderBy('code', 'desc')
            ->first();
        
        // Extract number from code and increment
        $nextCode = 's01';
        if ($lastStaff && preg_match('/s(\d+)/', $lastStaff->code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
            $nextCode = 's' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        }
        
        return view('staff.create', compact('nextCode'));
    }

    /**
     * Store a newly created staff in database.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'role' => 'required|in:staff,handyman,gardener',
            'code' => 'required|unique:users,code|regex:/^[shg]\d{2}$/',
            'password' => 'required|min:6',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
    
        // Create worker account
        User::create([
            'code' => $validated['code'],
            'password' => Hash::make($validated['password']),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
        ]);
    
        return redirect()->route('staff.index')
            ->with('success', 'Worker created successfully!');
    }

    /**
     * Show the form for editing a staff.
     */
    public function edit(User $staff)
    {
        // Check if this is a staff member
        if ($staff->role !== 'staff') {
            abort(404);
        }
        
        return view('staff.edit', compact('staff'));
    }

    /**
     * Update staff in database.
     */
    public function update(Request $request, User $staff)
    {
        // Check if this is a staff member
        if ($staff->role !== 'staff') {
            abort(404);
        }
        
        // Validate input
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
        ]);
        
        // Update staff details
        $staff->name = $validated['name'];
        $staff->phone = $validated['phone'];
        
        // Update password if provided
        if ($validated['password']) {
            $staff->password = Hash::make($validated['password']);
        }
        
        $staff->save();
        
        return redirect()->route('staff.index')
            ->with('success', 'Staff updated successfully!');
    }

    /**
     * Delete staff from database.
     */
    public function destroy(User $staff)
    {
        // Check if this is a staff member
        if ($staff->role !== 'staff') {
            abort(404);
        }
        
        // Delete staff
        $staff->delete();
        
        return redirect()->route('staff.index')
            ->with('success', 'Staff deleted successfully!');
    }
    /**
     * Export staff list as PDF
     */
    public function exportPdf()
    {
        $staff = User::whereIn('role', ['staff', 'handyman', 'gardener'])
            ->orderBy('code')
            ->get();

        $pdf = Pdf::loadView('staff.pdf', compact('staff'));
        
        return $pdf->download('staff-list-' . now()->format('Y-m-d') . '.pdf');
    }
}