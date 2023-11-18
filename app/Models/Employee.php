<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'employees';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'salery',
        'address',
        'job_description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function employeeEmployeeFinancials()
    {
        return $this->hasMany(EmployeeFinancial::class, 'employee_id', 'id');
    }

    public function calc_financials($month, $year){
        return $this->employeeEmployeeFinancials()->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->sum('amount') + $this->salery;;
    }
    
    public function expenses()
    {
        return $this->morphMany(Expense::class, 'model');
    } 
}
