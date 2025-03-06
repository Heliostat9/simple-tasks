<?php

namespace App\Repositories;

use App\Models\Task;
use App\Requests\TaskSortRequest;
use Illuminate\Support\Facades\Log;


class TaskRepository
{
    public const ROWS_PER_PAGE = 20;
    public function getAll(array $sortAttr)
    {

        if (!empty($sortAttr)) {
            $by = $sortAttr['sort_by'] ?? 'created_at';
            $order = $sortAttr['sort_order'] ?? 'asc';

            return Task::orderBy(
                $by,
                $order
            )->paginate(self::ROWS_PER_PAGE);
        }

        return Task::paginate(self::ROWS_PER_PAGE);
    }

    public function getById(int $id): ?Task
    {
        return Task::find($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(array $data, int $id): bool
    {
        $task = $this->getById($id);

        $updated = $task?->update($data) ?? false;

        if ($updated) {
            Log::info("Task #{$id} updated successfully");
        } else {
            Log::info("Task #{$id} not updated");
        }

        return $updated;
    }

    public function delete(int $id): bool
    {
        $task = $this->getById($id);

        $deleted = $task?->delete() ?? false;

        if ($deleted) {
            Log::info("Task #{$id} deleted");
        } else {
            Log::error("Task #{$id} not found");
        }

        return $deleted;
    }
}
