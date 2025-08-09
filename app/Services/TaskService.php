<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskService
{
    /**
     * Display a listing of the resource.
     */
    public function findAll(array $data): LengthAwarePaginator;

    /**
     * Display the specified resource.
     */
    public function findById(string $id): Task;

    /**
     * Store a newly created resource in storage.
     */
    public function create(array $data): Task;

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, array $data): Task;

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id): bool;

    /**
     * Update the status of a task (e.g., mark as completed).
     */
    public function setStatus(string $id, string $status): Task;
}
