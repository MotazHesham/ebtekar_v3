<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'incomes';

    protected $dates = [
        'entry_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ]; 

    public const MODEL_TPYE_SELECT = [
        'App\Models\ReceiptBranch'        => 'ReceiptBranch', 
        'App\Models\ReceiptClient'        => 'ReceiptClient', 
        'App\Models\ReceiptSocial'        => 'ReceiptSocial', 
        'App\Models\Order'        => 'Order', 
        'App\Models\Material'        => 'Material', 
        'App\Models\FinancialAccount'        => 'FinancialAccount', 
    ];

    protected $fillable = [
        'income_category_id',
        'entry_date',
        'amount',
        'quantity',
        'description',
        'model_id',
        'model_type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function income_category()
    {
        return $this->belongsTo(IncomeCategory::class, 'income_category_id');
    }

    
    public function model()
    {
        return $this->morphTo();
    }

    public function getEntryDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setEntryDateAttribute($value)
    {
        $this->attributes['entry_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
