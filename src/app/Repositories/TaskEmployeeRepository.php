<?php

namespace App\Repositories;

use App\Models\EmployeeTask;
use Illuminate\Support\Facades\DB;

class TaskEmployeeRepository
{
    public function __construct(
        private TaskRepository $taskRepository,
        private EmployeeRepository $employeeRepository
    ) {
    }

    public function create(array $employeeIds, int $taskId): bool
    {
        $employees = $this->employeeRepository->getByIds($employeeIds);
        $activeEmployees = $employees->where('status', 'work')->pluck('id')->toArray();

        $employeesForInsert = [];

        foreach ($activeEmployees as $employeeId) {
            $employeesForInsert[] = [
                'employee_id' => $employeeId,
                'task_id' => $taskId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        return DB::table('employee_tasks')->insert($employeesForInsert);
    }

    public function update(array $employeeIds, int $taskId): void
    {
        $task = $this->taskRepository->getById($taskId);
        $employees = $this->employeeRepository->getByIds($employeeIds);
        $activeEmployees = $employees->where('status', 'work')->pluck('id');

        $task?->employees()->sync($activeEmployees);
    }

    public function delete(int $taskId): void
    {
        $task = $this->taskRepository->getById($taskId);

        $task?->employees()->detach();
    }
}
