<?php

use App\Http\Controllers\Api\V1\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\TicketController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::apiResource('tickets', TicketController::class);

        Route::get('/tickets/{ticket}/comments', [CommentController::class, 'index']);
        Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store']);

        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

        Route::apiResource('categories', CategoryController::class);

    });

    Route::middleware('auth:sanctum')->prefix('admin')->group(function(){

        Route::get('/users', [UserController::class,'index']);

        Route::put('/users/{user}/role', [UserController::class,'updateRole']);

    });

});

