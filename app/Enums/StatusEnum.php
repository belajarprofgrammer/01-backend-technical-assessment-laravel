<?php

namespace App\Enums;

enum StatusEnum: string
{
    /**
     * define the status as pending.
     */
    case PENDING = 'pending';

    /**
     * define the status as in progress.
     */
    case INPROGRESS = 'in_progress';

    /**
     * define the status as completed.
     */
    case COMPLETED = 'completed';
}
