<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create actual homestay units
        $units = [
            [
                'unit_name' => 'Masam Masam Manis',
                'unit_size' => 'small',
                'max_staff_allowed' => 2,
            ],
            [
                'unit_name' => 'Anak Bapak',
                'unit_size' => 'large',
                'max_staff_allowed' => 4,
            ],
            [
                'unit_name' => 'Ibu Mertuaku',
                'unit_size' => 'large',
                'max_staff_allowed' => 4,
            ],
            [
                'unit_name' => 'Unit Baru',
                'unit_size' => 'large',
                'max_staff_allowed' => 4,
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}