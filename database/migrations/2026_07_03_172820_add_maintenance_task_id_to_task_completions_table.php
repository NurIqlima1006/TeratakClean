<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_completions', function (Blueprint $table) {

            $table->unsignedBigInteger('maintenance_task_id')
                ->nullable()
                ->after('housekeeping_task_id');

            $table->foreign('maintenance_task_id')
                ->references('id')
                ->on('maintenance_tasks')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::table('task_completions', function (Blueprint $table) {

            $table->dropForeign(['maintenance_task_id']);
            $table->dropColumn('maintenance_task_id');

        });
    }
};