<?php

namespace App\Enums;

enum RecurringIntervalEnum: string
{
    /**
     * define the recurring interval as daily.
     */
    case DAILY = 'daily';

    /**
     * define the recurring interval as weekly.
     */
    case WEEKLY = 'weekly';

    /**
     * define the recurring interval as monthly.
     */
    case MONTHLY = 'monthly';
}
