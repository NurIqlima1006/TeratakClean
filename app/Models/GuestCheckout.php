<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestCheckout extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'checkout_date',
    ];

    protected $casts = [
        'checkout_date' => 'date',
    ];

    // Relationships

    /**
     * Get the unit for this checkout
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}