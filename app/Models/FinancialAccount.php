<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialAccount extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'financial_accounts';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'account',
        'description',
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function receipts_social(){
        return $this->hasMany(ReceiptSocial::class, 'financial_account_id');
    }

    public function receipts_client(){
        return $this->hasMany(ReceiptClient::class, 'financial_account_id');
    }
    
    public function incomes()
    {
        return $this->morphMany(Income::class, 'model');
    } 

    public function calculate_deposits(){
        $receipts_total_deposit =  $this->receipts_social()->sum('deposit') + $this->receipts_client()->sum('deposit');
        $deposits_withdrawn =  $this->incomes()->sum('amount');
        return $receipts_total_deposit - $deposits_withdrawn;
    }
}
