<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousekeepingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'cleaning_task_id',
        'guest_checkout_date',
        'status',
        'assigned_staff_id',
        'completed_at',
    ];

    protected $casts = [
        'guest_checkout_date' => 'date',
        'completed_at' => 'datetime',
    ];

    // Relationships

    /**
     * Get the unit for this task
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the cleaning task template
     */
    public function cleaningTask()
    {
        return $this->belongsTo(CleaningTask::class);
    }

    /**
     * Get the assigned staff member
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