<?php

namespace App\Jobs;

use App\Models\CalendarDate;
use App\Services\CalendarDateReminderNotifier;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCalendarDateReminderJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $uniqueFor = 3600;

    public function __construct(
        public int $calendarDateId,
        public int $occurrenceYear,
    ) {
    }

    public function uniqueId(): string
    {
        return $this->calendarDateId . '-' . $this->occurrenceYear;
    }

    public function handle(CalendarDateReminderNotifier $notifier): void
    {
        $calendarDate = CalendarDate::with('user')->find($this->calendarDateId);

        if (! $calendarDate || ! $calendarDate->user || $calendarDate->user->blocked) {
            return;
        }

        if ($calendarDate->reminder_sent_for_year === $this->occurrenceYear) {
            return;
        }

        $occurrenceDate = Carbon::parse($calendarDate->event_date)->year($this->occurrenceYear)->startOfDay();
        $daysUntilEvent = Carbon::today()->diffInDays($occurrenceDate, false);

        if ($daysUntilEvent < 0) {
            return;
        }

        $notifier->send($calendarDate->user, $calendarDate, $occurrenceDate, $daysUntilEvent);

        $calendarDate->update([
            'reminder_sent_for_year' => $this->occurrenceYear,
        ]);
    }
}
