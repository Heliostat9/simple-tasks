<?php

namespace App\Http\Controllers;

use App\Requests\TaskRequest;
use App\Requests\TaskSortRequest;
use App\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(TaskSortRequest $sortRequest): JsonResponse
    {
        $tasks = $this->taskService->getAllTasks($sortRequest->array());
        return TaskResource::collection($tasks)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $employeeIds = $request->get('employeeIds');
        $taskInfo = $request->except('employeeIds');

        $task = $this->taskService->createTask(
            $taskInfo,
            $employeeIds
        );

        return $task
            ? response()->json(sprintf("Task with id:%d created", $task->id), 201)
            : response()->json("Task not created", 422);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);

        return $task
            ? (new TaskResource($task))->response()
            : response()->json('Task not found', 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, int $id): JsonResponse
    {
        $employeeIds = $request->get('employeeIds');
        $taskInfo = $request->except('employeeIds');

        return $this->taskService->updateTask($id, $taskInfo, $employeeIds)
            ? response()->json(sprintf("Task with id:%d was updated", $id))
            : response()->json('Task was not found', 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->taskService->deleteTask($id)
            ? response()->json(sprintf("Task with id:%d was deleted", $id))
            : response()->json('Task was not found', 404);
    }
}
