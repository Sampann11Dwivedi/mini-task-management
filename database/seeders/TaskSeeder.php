<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $priorities = ['Low', 'Medium', 'High'];
        $statuses = ['Pending', 'Completed'];

        for ($i = 1; $i <= 50; $i++) {
            Task::create([
                'title' => "Task $i",
                'description' => "This is the description for Task $i.",
                'priority' => $priorities[array_rand($priorities)],
                'due_date' => Carbon::now()->addDays(rand(1, 30))->format('Y-m-d'),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
