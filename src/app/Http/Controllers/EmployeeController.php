<?php

namespace App\Http\Controllers;

use App\Requests\EmployeeRequest;
use App\Resources\EmployeeResource;
use App\Resources\TaskResource;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function __construct(
        private EmployeeService $employeeService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $employees = $this->employeeService->getAllEmployees();
        return EmployeeResource::collection($employees)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request): JsonResponse
    {
        $employee = $this->employeeService->createEmployee($request->validated());

        return $employee
            ? response()->json(sprintf("Employee with id:%d created", $employee->id), 201)
            : response()->json("Employee not created", 422);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $employee  = $this->employeeService->getEmployeeById($id);

        return $employee
            ? (new TaskResource($employee))->response()
            : response()->json("Employee not found", 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, string $id): JsonResponse
    {
        return $this->employeeService->updateEmployee($id, $request->validated())
            ? response()->json(sprintf("Employee with id:%d edited", $id))
            : response()->json("Employee not edited", 422);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->employeeService->deleteEmployee($id)
            ? response()->json(sprintf("Employee with id:%d deleted", $id))
            : response()->json("Employee not found", 404);
    }
}
