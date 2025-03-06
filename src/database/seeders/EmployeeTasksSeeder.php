<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Task;
use Illuminate\Database\Seeder;

class EmployeeTasksSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taskIds = Task::pluck('id')->toArray();

        Employee::each(static function ($employee) use ($taskIds) {
            $employee->tasks()->attach(
                collect($taskIds)->random(random_int(1, count($taskIds)))
            );
        });
    }
}
