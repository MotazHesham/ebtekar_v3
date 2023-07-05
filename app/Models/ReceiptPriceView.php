<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptPriceView extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'receipt_price_views';

    public static $searchable = [
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
        'order_num',
        'date_of_receiving_order',
        'client_name',
        'phone_number',
        'total_cost',
        'place',
        'relate_duration',
        'supply_duration',
        'payment',
        'added_value',
        'printing_times',
        'staff_id',
        'website_setting_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function receiptPriceViewReceiptPriceViewProducts()
    {
        return $this->hasMany(ReceiptPriceViewProduct::class, 'receipt_price_view_id', 'id');
    }

    public function getDateOfReceivingOrderAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateOfReceivingOrderAttribute($value)
    {
        $this->attributes['date_of_receiving_order'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function website(){
        return $this->belongsTo(WebsiteSetting::class,'website_setting_id');
    }
	// operations
	public function calc_added_value(){ 
		return round( ( ($this->total_cost * 14) / 100 ) , 2);
	}

	public function calc_total_cost(){
		return $this->total_cost + $this->calc_added_value();
	} 
}
