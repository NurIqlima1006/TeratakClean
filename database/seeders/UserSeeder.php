<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'code' => 'admin_001',
            'password' => Hash::make('admin123'),
            'name' => null,
            'phone' => null,
            'role' => 'admin',
        ]);

        // Owner
        User::create([
            'code' => 'owner_001',
            'password' => Hash::make('owner123'),
            'name' => null,
            'phone' => null,
            'role' => 'owner',
        ]);

        // Staff (Cleaning)
        User::create([
            'code' => 's01',
            'password' => Hash::make('staff123'),
            'name' => 'Ahmad',
            'phone' => '0123456789',
            'role' => 'staff',
        ]);

        User::create([
            'code' => 's02',
            'password' => Hash::make('staff123'),
            'name' => 'Fatima',
            'phone' => '0123456790',
            'role' => 'staff',
        ]);

        User::create([
            'code' => 's03',
            'password' => Hash::make('staff123'),
            'name' => 'Ali',
            'phone' => '0123456791',
            'role' => 'staff',
        ]);

        // Handyman
        User::create([
            'code' => 'h01',
            'password' => Hash::make('handyman123'),
            'name' => 'Budi',
            'phone' => '0123456792',
            'role' => 'handyman',
        ]);

        // Gardener
        User::create([
            'code' => 'g01',
            'password' => Hash::make('gardener123'),
            'name' => 'Hassan',
            'phone' => '0123456793',
            'role' => 'gardener',
        ]);
    }
}