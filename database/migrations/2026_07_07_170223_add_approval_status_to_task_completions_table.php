<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_completions', function (Blueprint $table) {

            $table->enum('approval_status', [
                'pending',
                'approved'
            ])->default('pending');

            $table->unsignedBigInteger('approved_by')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('task_completions', function (Blueprint $table) {

            $table->dropColumn([
                'approval_status',
                'approved_by',
                'approved_at'
            ]);

        });
    }
};