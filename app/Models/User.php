<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'code',
        'password',
        'name',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships

    /**
     * Get all housekeeping tasks assigned to this staff
     */
    public function housekeepingTasks()
    {
        return $this->hasMany(HousekeepingTask::class, 'assigned_staff_id');
    }

    /**
     * Get all task completions by this staff
     */
    public function taskCompletions()
    {
        return $this->hasMany(TaskCompletion::class, 'staff_id');
    }

    /**
     * Get all maintenance tasks assigned to this staff
     */
    public function maintenanceTasks()
    {
        return $this->hasMany(MaintenanceTask::class, 'assigned_staff_id');
    }

    // Helper Methods

    /**
     * Get count of pending housekeeping tasks for this staff
     */
    public function getPendingTasksCount()
    {
        return $this->housekeepingTasks()
            ->where('status', 'pending')
            ->count();
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is Owner
     */
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    /**
     * Check if user is Staff
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }
}