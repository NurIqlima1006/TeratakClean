<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_name',
        'unit_size',
        'max_staff_allowed',
    ];

    // Relationships

    /**
     * Get all housekeeping tasks for this unit
     */
    public function housekeepingTasks()
    {
        return $this->hasMany(HousekeepingTask::class);
    }

    /**
     * Get all guest checkouts for this unit
     */
    public function guestCheckouts()
    {
        return $this->hasMany(GuestCheckout::class);
    }

    /**
     * Get all maintenance tasks for this unit
     */
    public function maintenanceTasks()
    {
        return $this->hasMany(MaintenanceTask::class);
    }
}