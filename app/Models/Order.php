<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'orders';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('completed', function (Builder $builder) {
            $builder->where('completed', 1);
        });
    }

    public static $searchable = [
        'order_num',
        'client_name',
        'phone_number',
    ];

    public const PAYMENT_STATUS_SELECT = [
        'unpaid' => 'un Paid',
        'paid'   => 'Paid',
    ];

    public const ORDER_TYPE_SELECT = [
        'customer' => 'Customer',
        'seller'   => 'Seller',
    ];

    public const PAYMENT_TYPE_SELECT = [
        'cash_on_delivery' => 'Cash On Delivery',
        'paymob'           => 'Paymob',
        'wallet'           => 'Wallet',
    ];

    public const COMMISSION_STATUS_SELECT = [
        'pending'   => 'Pending',
        'requested' => 'Requested',
        'delivered' => 'Delivered',
    ];

    public const DEPOSIT_TYPE_SELECT = [
        'Vodafon cash'  => 'Vodafon cash',
        'Etisalat cash' => 'Etisalat cash',
        'Bank Account'  => 'Bank Account',
        'Cash'          => 'Cash',
    ];

    protected $dates = [
        'done_time',
        'send_to_delivery_date',
        'send_to_playlist_date',
        'date_of_receiving_order',
        'excepected_deliverd_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const PLAYLIST_STATUS_SELECT = [
        'pending'       => 'Pending',
        'design'        => 'Design',
        'manufacturing' => 'Manufacturing',
        'prepare'       => 'Prepare',
        'shipment'      => 'Shipment',
        'finish'        => 'Finish',
    ];

    public const DELIVERY_STATUS_SELECT = [
        'pending'     => 'Pending',
        'on_review'   => 'on Review',
        'on_delivery' => 'on Delivery',
        'delivered'   => 'Delivered',
        'delay'       => 'Delay',
        'cancel'      => 'Cancel',
    ];

    protected $fillable = [
        'paymob_orderid',
        'order_type',
        'exchange_rate',
        'symbol',
        'order_num',
        'client_name',
        'phone_number',
        'phone_number_2',
        'shipping_address', 
        'shipping_country_cost',
        'shipping_cost_by_seller',
        'free_shipping',
        'free_shipping_reason',
        'printing_times',
        'completed',
        'calling',
        'quickly',
        'supplied',
        'client_review',
        'done',
        'returned',
        'hold',
        'hold_reason',
        'done_time',
        'send_to_delivery_date',
        'send_to_playlist_date',
        'date_of_receiving_order',
        'excepected_deliverd_date',
        'playlist_status',
        'payment_status',
        'delivery_status',
        'payment_type',
        'commission_status',
        'deposit_type',
        'deposit_amount',
        'total_cost_by_seller',
        'total_cost',
        'commission',
        'extra_commission',
        'discount',
        'discount_code',
        'note',
        'cancel_reason',
        'delay_reason',
        'user_id',
        'shipping_country_id',
        'designer_id',
        'preparer_id',
        'manufacturer_id',
        'shipmenter_id',
        'reviewer_id',
        'delivery_man_id',
        'website_setting_id',
        'social_user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function getDoneTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDoneTimeAttribute($value)
    {
        $this->attributes['done_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getSendToDeliveryDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setSendToDeliveryDateAttribute($value)
    {
        $this->attributes['send_to_delivery_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getSendToPlaylistDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setSendToPlaylistDateAttribute($value)
    {
        $this->attributes['send_to_playlist_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getDateOfReceivingOrderAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateOfReceivingOrderAttribute($value)
    {
        $this->attributes['date_of_receiving_order'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getExcepectedDeliverdDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setExcepectedDeliverdDateAttribute($value)
    {
        $this->attributes['excepected_deliverd_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function shipping_country()
    {
        return $this->belongsTo(Country::class, 'shipping_country_id')->withTrashed();
    }

    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id')->withTrashed();
    }

    public function preparer()
    {
        return $this->belongsTo(User::class, 'preparer_id')->withTrashed();
    }

    public function manufacturer()
    {
        return $this->belongsTo(User::class, 'manufacturer_id')->withTrashed();
    }

    public function shipmenter()
    {
        return $this->belongsTo(User::class, 'shipmenter_id')->withTrashed();
    }

    public function delivery_man()
    {
        return $this->belongsTo(User::class, 'delivery_man_id')->withTrashed();
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id')->withTrashed();
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
    
    public function website(){
        return $this->belongsTo(WebsiteSetting::class,'website_setting_id');
    }

	// operations 

	public function calc_discount(){ 
		return $this->discount;
	}

	public function calc_total_cost(){
		return $this->total_cost + $this->extra_commission;
	}

	public function calc_total_for_delivery(){
		return $this->total_cost + $this->extra_commission  - $this->deposit_amount;
	}

	public function calc_total(){
		return $this->total_cost + $this->extra_commission + $this->shipping_country_cost;
	}

	public function calc_total_for_client(){
		return $this->calc_total() - $this->deposit_amount - $this->calc_discount();
	}

    public function get_products_details(){
        $this->load('orderDetails.product');
        $description = '';
        $i = 0;
        $len = count($this->orderDetails);
        foreach($this->orderDetails as $detail){
            $description .= $detail->product->name . ' (عدد ' . $detail->quantity . ')';
            if($i != $len - 1){
                $description .= ' - ';
            }
            $i++;
        }
        return $description;
    }
    public function incomes()
    {
        return $this->morphMany(Income::class, 'model');
    }
    
    public function add_income(){ 
        Income::create([ 
            'income_category_id' => 4,
            'entry_date' => date(config('panel.date_format')),
            'amount' => $this->calc_total() - $this->calc_discount(),
            'description' => $this->order_num . '-' . exchange_rate($this->calc_total_for_client(),$this->exchange_rate) . $this->symbol ,
            'model_id' => $this->id,
            'model_type' => 'App\Models\Order',
        ]);
        return 1;
    }
}
