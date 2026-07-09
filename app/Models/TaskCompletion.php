<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
    'housekeeping_task_id',
    'maintenance_task_id',
    'staff_id',
    'image_path',
    'completion_notes',

    'approval_status',
    'approved_by',
    'approved_at',
];

    /**
     * Housekeeping Task
     */
    public function housekeepingTask()
    {
        return $this->belongsTo(HousekeepingTask::class);
    }

    /**
     * Maintenance Task
     */
    public function maintenanceTask()
    {
        return $this->belongsTo(MaintenanceTask::class);
    }

    /**
     * Staff who completed the task
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}