<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'task_name',
        'description',
        'frequency_days',
        'assigned_staff_id',
        'scheduled_date',
        'status',
        'completion_date',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completion_date' => 'datetime',
    ];

    // Relationships

    /**
     * Get the unit for this maintenance task
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the assigned staff member (handyman/gardener)
     */
    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    /**
     * Get task completion
     */
    public function completion()
    {
        return $this->hasOne(TaskCompletion::class);
    }
}