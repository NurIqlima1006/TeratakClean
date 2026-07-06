<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_completions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('housekeeping_task_id');
            $table->unsignedBigInteger('staff_id');
            $table->string('image_path');
            $table->text('completion_notes')->nullable();
            $table->timestamps();

            $table->foreign('housekeeping_task_id')
                ->references('id')
                ->on('housekeeping_tasks')
                ->onDelete('cascade');

            $table->foreign('staff_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_completions');
    }
};