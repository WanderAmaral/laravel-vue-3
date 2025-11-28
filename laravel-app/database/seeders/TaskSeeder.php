<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::insert([
            [
                'name' => 'Create login page',
                'started_at' => Carbon::now()->subDays(2),
                'finished_at' => Carbon::now()->subDay(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Task 2',
                'started_at' => Carbon::now()->subDay(),
                'finished_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Task 4',
                'started_at' => Carbon::now()->subDay(),
                'finished_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Task 5',
                'started_at' => Carbon::now()->subDay(),
                'finished_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
