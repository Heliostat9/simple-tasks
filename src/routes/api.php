<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;

Route::apiResource('employees', EmployeeController::class);
Route::apiResource('tasks', TaskController::class)->except('store');
Route::post('tasks', [TaskController::class, 'store'])
    ->middleware('throttle:2,1');
