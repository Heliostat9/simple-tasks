<?php

namespace App\Services;

use App\Jobs\CheckUnassignedTasks;
use App\Models\Task;
use App\Repositories\TaskEmployeeRepository;
use App\Repositories\TaskRepository;
use App\Requests\TaskSortRequest;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskService
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly TaskEmployeeRepository $taskEmployeeRepository
    ) {
    }

    public function getAllTasks(array $sortAttr)
    {
        return $this->taskRepository->getAll($sortAttr);
    }

    public function getTaskById(int $id): ?Task
    {
        return $this->taskRepository->getById($id);
    }

    public function createTask(array $taskInfo, array $employeeIds): Task|false
    {
        DB::beginTransaction();

        try {
            $task = $this->taskRepository->create($taskInfo);

            $this->taskEmployeeRepository->create(
                $employeeIds,
                $task->id
            );

            DB::commit();

            Log::info(sprintf("Task with id:%d was created!", $task->id));

            return $task;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function updateTask(
        int $id,
        array $taskInfo,
        array $employeeIds
    ): bool {
        DB::beginTransaction();

        try {
            $this->taskEmployeeRepository->update($employeeIds, $id);
            $updated = $this->taskRepository->update($taskInfo, $id);
            DB::commit();
            return $updated;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function deleteTask(int $id): bool
    {
        DB::beginTransaction();

        try {
            $this->taskEmployeeRepository->delete($id);
            $deleted = $this->taskRepository->delete($id);
            DB::commit();

            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Task deletion failed: ' . $e->getMessage());
            return false;
        }
    }
}
