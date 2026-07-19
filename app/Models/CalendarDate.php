<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarDate extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'calendar_dates';

    public const TYPE_SELECT = [
        'birthday' => 'عيد ميلاد',
        'wedding_anniversary' => 'ذكرى زواج',
        'engagement_anniversary' => 'ذكرى خطوبة',
        'graduation' => 'تخرج',
        'baby_birth' => 'مولود جديد',
        'other' => 'أخرى',
    ];

    protected $dates = [
        'event_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'event_date',
        'reminder_days_before',
        'reminder_sent_for_year',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getEffectiveReminderDaysBeforeAttribute(): int
    {
        return $this->reminder_days_before ?? (int) getSetting('calendar_default_reminder_days', 7);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_SELECT[$this->type] ?? $this->type;
    }

    public function getNextOccurrence(?Carbon $from = null): Carbon
    {
        $from = ($from ?? Carbon::today())->copy()->startOfDay();
        $eventDate = Carbon::parse($this->event_date)->startOfDay();
        $occurrence = $eventDate->copy()->year($from->year);

        if ($occurrence->lt($from)) {
            $occurrence->addYear();
        }

        return $occurrence;
    }

    public function getReminderDate(?Carbon $from = null): Carbon
    {
        return $this->getNextOccurrence($from)->subDays($this->effective_reminder_days_before);
    }

    public function isReminderDueOn(Carbon $date): bool
    {
        $date = $date->copy()->startOfDay();
        $occurrence = $this->getNextOccurrence($date);

        if ($this->reminder_sent_for_year === $occurrence->year) {
            return false;
        }

        return $this->getReminderDate($date)->isSameDay($date);
    }
}
