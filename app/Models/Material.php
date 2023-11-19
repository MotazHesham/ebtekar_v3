<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'materials';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'remaining',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function incomes()
    {
        return $this->morphMany(Income::class, 'model');
    }
    
    public function expenses()
    {
        return $this->morphMany(Expense::class, 'model');
    }

    public function stock(){
        $incomes = collect($this->incomes);
        $incomes->each(function ($item) {
            $item['type'] = 'out';
        });
        $expenses = collect($this->expenses);
        $expenses->each(function ($item) {
            $item['type'] = 'in';
        });
        $merge = $incomes->merge($expenses);
        return $merge->sortBy('entry_date')->reverse()->values()->all();
    }
}
