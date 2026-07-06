<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');
            $table->string('task_name');
            $table->text('description')->nullable();
            $table->integer('frequency_days')->default(14);
            $table->unsignedBigInteger('assigned_staff_id')->nullable();
            $table->date('scheduled_date');
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->dateTime('completion_date')->nullable();
            $table->timestamps();

            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade');

            $table->foreign('assigned_staff_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_tasks');
    }
};
