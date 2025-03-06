<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    public function __construct(
        private EmployeeRepository $employeeRepository
    ) {
    }

    public function getAllEmployees(): LengthAwarePaginator
    {
        return $this->employeeRepository->getAll();
    }

    public function getEmployeeById(int $id): ?Employee
    {
        return $this->employeeRepository->getById($id);
    }

    public function createEmployee(array $data): Employee|false
    {
        try {
            $employee = $this->employeeRepository->create($data);

            if (!$employee) {
                Log::error("Failed to create employee");
                return false;
            }

            Log::info(sprintf("Employee with id:%d was created!", $employee->id));
            return $employee;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function updateEmployee(int $id, array $data): bool
    {
        try {
            return $this->employeeRepository->update($data, $id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function deleteEmployee(int $id): bool
    {
        try {
            return $this->employeeRepository->delete($id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
