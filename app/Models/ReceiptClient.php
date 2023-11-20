<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptClient extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'receipt_clients';

    public static $searchable = [
        'order_num',
        'client_name',
        'phone_number',
    ];

    protected $dates = [
        'date_of_receiving_order',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'date_of_receiving_order',
        'order_num',
        'client_name',
        'phone_number',
        'deposit',
        'deposit_type',
        'discount',
        'note',
        'total_cost',
        'done',
        'quickly',
        'printing_times',
        'staff_id',
        'financial_account_id',
        'website_setting_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function receiptsReceiptClientProducts()
    {
        return $this->hasMany(ReceiptClientProductPivot::class,'receipt_client_id');
    }

    public function getDateOfReceivingOrderAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateOfReceivingOrderAttribute($value)
    {
        $this->attributes['date_of_receiving_order'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    
    public function financial_account()
    {
        return $this->belongsTo(FinancialAccount::class, 'financial_account_id')->withTrashed();
    }
    
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function website(){
        return $this->belongsTo(WebsiteSetting::class,'website_setting_id');
    }
	// operations
	public function calc_discount(){
		$total = $this->total_cost / 100;
		return round( ($total * $this->discount ) , 2);
	}

	public function calc_total_cost(){
		return $this->total_cost - $this->calc_discount();
	}

	public function calc_total_for_client(){
		return $this->total_cost - $this->calc_discount()  - $this->deposit;
	}
    
    public function incomes()
    {
        return $this->morphMany(Income::class, 'model');
    } 
    
    public function add_income(){ 
        Income::create([ 
            'income_category_id' => 2,
            'entry_date' => date(config('panel.date_format')),
            'amount' => $this->calc_total_for_client(),
            'description' => $this->order_num,
            'model_id' => $this->id,
            'model_type' => 'App\Models\ReceiptClient',
        ]);
        return 1;
    }
}
