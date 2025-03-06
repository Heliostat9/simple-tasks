<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class EmployeeRepository
{
    public function getAll(): LengthAwarePaginator
    {
        return Employee::paginate(10);
    }

    public function getById(int $id): ?Employee
    {
        return Employee::where('id', $id)->first();
    }

    public function getByIds(array $ids): Collection
    {
        return Employee::whereIn('id', $ids)->get();
    }

    public function create(array $data): ?Employee
    {
        return Employee::create($data);
    }

    public function update(array $data, int $id): bool
    {
        $employee = $this->getById($id);

        $updated = $employee?->update($data) ?? false;

        if ($updated) {
            Log::info("Employee #{$id} updated");
        } else {
            Log::info("Employee #{$id} not updated");
        }

        return $updated;
    }

    public function delete(int $id): bool
    {
        $employee = $this->getById($id);

        $deleted = $employee?->delete() ?? false;

        if ($deleted) {
            Log::info("Employee #{$id} deleted");
        } else {
            Log::info("Employee #{$id} not deleted");
        }

        return $deleted;
    }
}
