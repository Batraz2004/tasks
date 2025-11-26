<?php

use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/profile', function (Request $request) {
    $user = $request->user();
    return response()->json([
        'data' => $user,
    ]);
})->middleware('auth:sanctum');

Route::post('registration', [RegistrationController::class, 'createUser']);
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('task')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [TaskController::class, 'list']);
    Route::post('/', [TaskController::class, 'create']);
    Route::get('{id}', [TaskController::class, 'getById']);
    Route::put('{id}', [TaskController::class, 'update']);
    Route::delete('{id}', [TaskController::class, 'deleteById']);
});
