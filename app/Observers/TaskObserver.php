<?php

namespace App\Observers;

use App\Enums\RecurringIntervalEnum;
use App\Enums\StatusEnum;
use App\Models\Task;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class TaskObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if ($task->is_recurring && $task->status === StatusEnum::COMPLETED) {
            $task->due_date = match ($task->recurring_interval) {
                RecurringIntervalEnum::DAILY => $task->due_date->addDay(),
                RecurringIntervalEnum::WEEKLY => $task->due_date->addWeek(),
                RecurringIntervalEnum::MONTHLY => $task->due_date->addMonth(),
            };

            Task::create([
                'user_id' => $task->user_id,
                'title' => $task->title,
                'description' => $task->description,
                'due_date' => $task->due_date,
                'is_recurring' => $task->is_recurring,
                'recurring_interval' => $task->recurring_interval,
            ]);
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
