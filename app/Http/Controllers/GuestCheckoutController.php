<?php

namespace App\Http\Controllers;

use App\Models\GuestCheckout;
use App\Models\Unit;
use App\Models\HousekeepingTask;
use App\Models\CleaningTask;
use Illuminate\Http\Request;

class GuestCheckoutController extends Controller
{
    /**
     * Display guest checkouts and assign tasks interface.
     */
    public function index(Request $request)
    {
        // Get the date filter (default to today)
        $date = $request->input('date', today()->toDateString());
        
        // Get all units with their checkouts
        $units = Unit::all();
        
        // Get checkouts for this date
        $checkouts = GuestCheckout::whereDate('checkout_date', $date)->get();
        
        // Get units with checkouts on this date
        $unitsWithCheckouts = $units->filter(function ($unit) use ($checkouts) {
            return $checkouts->where('unit_id', $unit->id)->count() > 0;
        });
        
        return view('housekeeping.checkouts.index', compact('units', 'checkouts', 'unitsWithCheckouts', 'date'));
    }

    /**
     * Show form to add new guest checkout.
     */
    public function create()
    {
        $units = Unit::all();
        return view('housekeeping.checkouts.create', compact('units'));
    }

    /**
     * Store a new guest checkout.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'checkout_date' => 'required|date|after_or_equal:today',
        ]);
        
        GuestCheckout::create($validated);
        
        return redirect()->route('housekeeping.checkouts')
            ->with('success', 'Guest checkout recorded successfully!');
    }

    /**
     * Delete a guest checkout.
     */
    public function destroy(GuestCheckout $checkout)
    {
        $checkout->delete();
        
        return redirect()->route('housekeeping.checkouts')
            ->with('success', 'Checkout deleted successfully!');
    }

    /**
     * Assign tasks for a specific date.
     * Generates all 9 cleaning tasks for each unit with a checkout on that date.
     */
    public function assignTasks(Request $request)
    {
        $date = $request->input('date', today()->toDateString());
        
        // Get all checkouts for this date that haven't been processed
        $checkouts = GuestCheckout::whereDate('checkout_date', $date)
            ->where('is_processed', false)
            ->get();
        
        if ($checkouts->isEmpty()) {
            return redirect()->route('housekeeping.checkouts')
                ->with('warning', 'No checkouts to process for this date.');
        }
        
        // Get all cleaning task templates
        $cleaningTaskTemplates = CleaningTask::all();
        
        $tasksCreated = 0;
        
        // For each checkout, create tasks for all cleaning templates
        foreach ($checkouts as $checkout) {
            foreach ($cleaningTaskTemplates as $template) {
                HousekeepingTask::create([
                    'unit_id' => $checkout->unit_id,
                    'cleaning_task_id' => $template->id,
                    'guest_checkout_date' => $checkout->checkout_date,
                    'status' => 'pending',
                    'assigned_staff_id' => null,
                ]);
                $tasksCreated++;
            }
            
            // Mark checkout as processed
            $checkout->update(['is_processed' => true]);
        }
        
        return redirect()->route('housekeeping.checkouts')
            ->with('success', "Successfully generated {$tasksCreated} tasks for {$checkouts->count()} unit(s)!");
    }
}