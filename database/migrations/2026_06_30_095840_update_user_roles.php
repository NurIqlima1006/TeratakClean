<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add new roles to enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'owner', 'staff', 'handyman', 'gardener') DEFAULT 'staff'");
    }

    public function down(): void
    {
        // Revert to old enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'owner', 'staff') DEFAULT 'staff'");
    }
};