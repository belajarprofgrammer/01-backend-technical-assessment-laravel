<?php

use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(TaskController::class)->group(function () {
    Route::get('tasks', 'index')
        ->name('tasks.index');
    Route::post('tasks', 'store')
        ->name('tasks.store');
    Route::get('tasks/{id}', 'show')
        ->whereNumber('id')
        ->name('tasks.show');
    Route::patch('tasks/{id}', 'update')
        ->whereNumber('id')
        ->name('tasks.update');
    Route::delete('tasks/{id}', 'destroy')
        ->whereNumber('id')
        ->name('tasks.destroy');
});

Route::patch('tasks/{id}/status', StatusController::class)
    ->whereNumber('id')
    ->name('status');
