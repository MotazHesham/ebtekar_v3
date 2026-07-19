<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'seasons';

    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'year',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value
            ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d')
            : null;
    }

    public function getEndDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value
            ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d')
            : null;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name . ' (' . $this->year . ')';
    }

    public function getRawStartDate(): ?string
    {
        return $this->attributes['start_date'] ?? null;
    }

    public function getRawEndDate(): ?string
    {
        return $this->attributes['end_date'] ?? null;
    }
}
