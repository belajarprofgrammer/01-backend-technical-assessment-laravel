<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskServiceImpl implements TaskService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected Task $task,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function findAll(array $data): LengthAwarePaginator
    {
        return $this->task->newQuery()
            ->when($data['keyword'], function (Builder $query, string $keyword) {
                $query->whereLike('title', "%{$keyword}%")
                    ->orWhereLike('description', "%{$keyword}%");
            })
            ->when($data['status'], function (Builder $query, string $status) {
                $query->where('status', '=', $status);
            })
            ->when($data['is_recurring'], function (Builder $query, bool $isRecurring) {
                $query->where('is_recurring', '=', $isRecurring);
            }, function (Builder $query) use ($data) {
                if (isset($data['is_recurring'])) {
                    $query->where('is_recurring', '=', false);
                }
            })
            ->when($data['recurring_interval'], function (Builder $query, string $recurringInterval) {
                $query->where('recurring_interval', '=', $recurringInterval);
            })
            ->when($data['due_date_from'], function (Builder $query, string $dueDateFrom) {
                $query->whereDate('due_date', '>=', $dueDateFrom);
            })
            ->when($data['due_date_to'], function (Builder $query, string $dueDataTo) {
                $query->whereDate('due_date', '<=', $dueDataTo);
            })
            ->paginate($data['per_page'])
            ->withQueryString();
    }

    /**
     * Display the specified resource.
     */
    public function findById(string $id): Task
    {
        $task = $this->task->find($id);

        if ($task === null) {
            throw new ModelNotFoundException("Task not found with id {$id}");
        }

        return $task;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(array $data): Task
    {
        return $this->task->create([
            'title' => $data['title'],
            'description' => $data['description'],
            'due_date' => $data['due_date'],
            'is_recurring' => $data['is_recurring'],
            'recurring_interval' => $data['recurring_interval'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, array $data): Task
    {
        $task = $this->task->find($id);

        if ($task === null) {
            throw new ModelNotFoundException("Task not found with id {$id}");
        }

        $task->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'due_date' => $data['due_date'],
            'is_recurring' => $data['is_recurring'],
            'recurring_interval' => $data['recurring_interval'],
        ]);

        return $task;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id): bool
    {
        $task = $this->task->find($id);

        if ($task === null) {
            throw new ModelNotFoundException("Task not found with id {$id}");
        }

        return $task->delete();
    }

    /**
     * Update the status of a task (e.g., mark as completed)
     */
    public function setStatus(string $id, string $status): Task
    {
        $task = $this->task->find($id);

        if ($task === null) {
            throw new ModelNotFoundException("Task not found with id {$id}");
        }

        $task->update([
            'status' => $status,
        ]);

        return $task;
    }
}
