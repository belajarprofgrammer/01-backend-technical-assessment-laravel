<?php

use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

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
