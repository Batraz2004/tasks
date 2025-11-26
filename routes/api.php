<?php

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
