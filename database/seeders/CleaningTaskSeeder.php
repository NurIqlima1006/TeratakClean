<?php

namespace Database\Seeders;

use App\Models\CleaningTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CleaningTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default 9 cleaning tasks for each unit
        $tasks = [
            ['task_name' => 'Toilet', 'description' => 'Clean and sanitize toilet and bathroom'],
            ['task_name' => 'Kitchen', 'description' => 'Clean kitchen counters, sink, and appliances'],
            ['task_name' => 'Living Room', 'description' => 'Dust furniture, vacuum, and tidy living area'],
            ['task_name' => 'Dining Area', 'description' => 'Clean and wipe dining table and chairs'],
            ['task_name' => 'Room 1', 'description' => 'Dust, vacuum, and organize Bedroom 1'],
            ['task_name' => 'Room 2', 'description' => 'Dust, vacuum, and organize Bedroom 2'],
            ['task_name' => 'Room 3', 'description' => 'Dust, vacuum, and organize Bedroom 3'],
            ['task_name' => 'Balcony', 'description' => 'Sweep and clean balcony area'],
            ['task_name' => 'Outdoor Area', 'description' => 'Clean and tidy outdoor/garden area'],
        ];

        foreach ($tasks as $task) {
            CleaningTask::create($task);
        }
    }
}