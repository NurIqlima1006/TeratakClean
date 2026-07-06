<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CleaningTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_name',
        'description',
    ];

    // Relationships

    /**
     * Get all housekeeping tasks using this template
     */
    public function housekeepingTasks()
    {
        return $this->hasMany(HousekeepingTask::class);
    }
}