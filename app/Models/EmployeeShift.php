<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    use HasFactory;

    protected $table = 'employee_shifts';

    protected $fillable = [
        'user_id',
        'type',
        'started_at',
        'ended_at',
        'status',
        'shift_date',
        'metrics',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'started_at',
        'ended_at',
        'shift_date',
        'created_at',
        'updated_at',
    ];
    
    public const SHIFT_TYPES = [
        'creator' => 'Creator',
        'operation' => 'Operation',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    } 
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workflowOperations()
    {
        return $this->hasMany(WorkflowOperation::class, 'shift_id');
    }

    protected $casts = [
        'metrics' => 'array',
    ];
}

