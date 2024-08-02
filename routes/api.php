<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('employees', [EmployeeController::class, 'store']);
        Route::get('posts', [PostController::class, 'index']);
        Route::post('posts', [PostController::class, 'store']);
        Route::put('posts/{post}', [PostController::class, 'update']);
        Route::delete('posts/{post}', [PostController::class, 'destroy']);
        Route::get('posts/employee/{employee}', [PostController::class, 'byEmployee']);
        Route::get('posts/category/{category}', [PostController::class, 'byCategory']);
    });
});
