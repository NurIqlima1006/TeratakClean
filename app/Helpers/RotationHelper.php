<?php

namespace App\Helpers;

use App\Models\Unit;
use Carbon\Carbon;

class RotationHelper
{
    /**
     * Hardcoded rotation schedule for handyman and gardener
     * Week 1 = Masam Masam Manis
     * Week 2 = Anak Bapak
     * Week 3 = Ibu Mertuaku
     * Week 4 = Unit Baru
     */
    private static $rotationSchedule = [
        1 => 'Masam Masam Manis',
        2 => 'Anak Bapak',
        3 => 'Ibu Mertuaku',
        4 => 'Unit Baru',
    ];

    /**
     * Get unit for current week (both handyman and gardener)
     */
    public static function getUnitForCurrentWeek()
    {
        $currentWeek = now()->weekOfYear;
        $cycleWeek = (($currentWeek - 1) % 4) + 1; // Rotate through weeks 1-4
        
        $unitName = self::$rotationSchedule[$cycleWeek];
        return Unit::where('unit_name', $unitName)->first();
    }

    /**
     * Get week number (1-4) for current date
     */
    public static function getCurrentCycleWeek()
    {
        $currentWeek = now()->weekOfYear;
        return (($currentWeek - 1) % 4) + 1;
    }
}