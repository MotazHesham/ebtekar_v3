<?php

namespace App\Services;

use App\Models\CalendarDate;
use App\Models\User;
use Carbon\Carbon;

class CalendarDateReminderNotifier
{
    /**
     * Deliver the calendar date reminder to the customer.
     *
     * @param  User  $user
     * @param  CalendarDate  $calendarDate
     * @param  Carbon  $occurrenceDate  The upcoming event date (this year's occurrence).
     * @param  int  $daysUntilEvent  Days remaining until the event.
     */
    public function send(User $user, CalendarDate $calendarDate, Carbon $occurrenceDate, int $daysUntilEvent): void
    {
        //
    }
}
