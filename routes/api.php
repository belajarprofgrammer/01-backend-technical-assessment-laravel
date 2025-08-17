<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', RegisterController::class)
        ->name('register');
    Route::post('login', LoginController::class)
        ->name('login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(TaskController::class)->group(function () {
        Route::get('tasks', 'index')
            ->name('tasks.index');
        Route::post('tasks', 'store')
            ->name('tasks.store');
        Route::get('tasks/{task}', 'show')
            ->name('tasks.show');
        Route::patch('tasks/{task}', 'update')
            ->name('tasks.update');
        Route::delete('tasks/{task}', 'destroy')
            ->name('tasks.destroy');
    });

    Route::patch('tasks/{task}/status', StatusController::class)
        ->name('status');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'show')
            ->name('profile.show');
        Route::patch('profile', 'update')
            ->name('profile.update');
        Route::delete('profile', 'destroy')
            ->name('profile.destroy');
    });

    Route::prefix('auth')->group(function () {
        Route::delete('logout', LogoutController::class)
            ->name('logout');
    });
});
