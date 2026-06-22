<?php

namespace App\Console\Commands;

use App\Jobs\SendCalendarDateReminderJob;
use App\Models\CalendarDate;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendCalendarDateReminders extends Command
{
    protected $signature = 'calendar-dates:send-reminders {date? : Date to evaluate reminders for (Y-m-d), defaults to today}';

    protected $description = 'Dispatch reminder jobs for customer calendar dates due today.';

    public function handle(): int
    {
        $date = $this->argument('date')
            ? Carbon::parse($this->argument('date'))->startOfDay()
            : Carbon::today();

        $dispatched = 0;

        CalendarDate::query()
            ->with('user')
            ->whereHas('user', fn ($query) => $query->where('blocked', 0))
            ->chunkById(100, function ($calendarDates) use ($date, &$dispatched) {
                foreach ($calendarDates as $calendarDate) {
                    if (! $calendarDate->isReminderDueOn($date)) {
                        continue;
                    }

                    $occurrenceYear = $calendarDate->getNextOccurrence($date)->year;

                    SendCalendarDateReminderJob::dispatch($calendarDate->id, $occurrenceYear);
                    $dispatched++;
                }
            });

        $this->info("Dispatched {$dispatched} calendar date reminder job(s) for {$date->toDateString()}.");

        return self::SUCCESS;
    }
}
