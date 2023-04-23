<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['api'], 'prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('update-profile/{id}', [AuthController::class, 'updateProfile']);
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::get('recent-tasks', [TaskController::class, 'recentTasks']);
    Route::get('all-tasks-count', [TaskController::class, 'allTasksCount']);
    Route::get('assigned-tasks-count', [TaskController::class, 'allAssignedTasksCount']);
    Route::apiResource('status', StatusController::class);
    Route::get('users', [UserController::class, 'index']);
    Route::get('all-users-count', [UserController::class, 'allUsersCount']);
});
