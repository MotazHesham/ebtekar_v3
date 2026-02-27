<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowOperation extends Model
{
    use HasFactory;

    protected $table = 'workflow_operations';

    protected $fillable = [
        'model_type',
        'model_id',
        'stage',
        'user_id',
        'shift_id',
        'started_at',
        'ended_at',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'started_at',
        'ended_at',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    } 

    public function model()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(EmployeeShift::class, 'shift_id');
    }
}

