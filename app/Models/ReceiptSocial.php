<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ReceiptSocial extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'receipt_socials';

    public static $searchable = [
        'order_num',
        'client_name',
        'phone_number',
    ];

    public const PAYMENT_STATUS_SELECT = [
        'unpaid' => 'unPaid',
        'paid'   => 'Paid',
    ];
    public const DISCOUNT_TYPE_SELECT = [
        'percentage' => 'نسبة',
        'fixed'   => 'قيمة',
    ];
    public const DEPOSIT_TYPE_SELECT = [
        'cash' => 'كاش',
        'wallet'   => 'محفظة ألكترونية',
        'bank'   => 'تحويل بنكي',
    ];

    public const CLIENT_TYPE_SELECT = [
        'individual' => 'فردي',
        'corporate'  => 'شركة',
    ];

    protected $dates = [
        'date_of_receiving_order',
        'deliver_date',
        'send_to_delivery_date',
        'send_to_playlist_date',
        'done_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const DELIVERY_STATUS_SELECT = [
        'pending'     => 'Pending',
        'on_review'   => 'on Review',
        'on_delivery' => 'on Delivery',
        'delivered'   => 'Delivered',
        'delay'       => 'Delay',
        'cancel'      => 'Cancel',
    ];

    public const PLAYLIST_STATUS_SELECT = [
        'pending'       => 'Pending',
        'design'        => 'Design',
        'manufacturing' => 'Manufacturing',
        'prepare'       => 'Prepare',
        'shipment'      => 'Shipment',
        'finish'        => 'Finish',
    ];

    protected $fillable = [
        'order_num',
        'client_name',
        'client_type',
        'phone_number',
        'phone_number_2',
        'discount_type',
        'discount',
        'discounted_amount',
        'deposit',
        'commission',
        'extra_commission', 
        'total_cost',
        'done',
        'quickly',
        'confirm',
        'returned',
        'supplied',
        'active_editing',
        'returned_to_design',
        'is_seasoned',
        'client_review',
        'hold',
        'hold_reason',
        'printing_times', 
        'shipping_country_cost', 
        'shipping_address',
        'date_of_receiving_order',
        'deliver_date',
        'send_to_delivery_date',
        'send_to_playlist_date',
        'done_time',
        'cancel_reason',
        'delay_reason',
        'delivery_status',
        'note',
        'payment_status',
        'deposit_type',
        'playlist_status',
        'staff_id',
        'designer_id',
        'preparer_id',
        'manufacturer_id',
        'shipmenter_id',
        'delivery_man_id',
        'shipping_country_id',
        'website_setting_id',
        'financial_account_id',
        'shopify_id',
        'shopify_order_num',
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
        return $value ? Carbon::parse($value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function getDateOfReceivingOrderAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateOfReceivingOrderAttribute($value)
    {
        $this->attributes['date_of_receiving_order'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getDeliverDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDeliverDateAttribute($value)
    {
        $this->attributes['deliver_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
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

    public function getDoneTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDoneTimeAttribute($value)
    {
        $this->attributes['done_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    } 

    public function receiptsReceiptSocialProducts()
    {
        return $this->hasMany(ReceiptSocialProductPivot::class,'receipt_social_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id')->withTrashed();
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

    public function shipping_country()
    {
        return $this->belongsTo(Country::class, 'shipping_country_id')->withTrashed();
    }

    public function financial_account()
    {
        return $this->belongsTo(FinancialAccount::class, 'financial_account_id')->withTrashed();
    }

    public function socials()
    {
        return $this->belongsToMany(Social::class)->withTrashed();
    }
    
    public function website(){
        return $this->belongsTo(WebsiteSetting::class,'website_setting_id');
    }
	// operations 

    public function followups()
    {
        return $this->hasMany(ReceiptSocialFollowup::class, 'receipt_social_id');
    }

    public function calc_discount(){
        if($this->discount_type == 'percentage'){
            return $this->total_cost * $this->discount / 100;
        }else{
            return $this->discount;
        }
    }

	public function calc_total_cost(){
		return $this->total_cost + $this->extra_commission - $this->discounted_amount;
	}

	public function calc_total_for_delivery(){
		return $this->total_cost + $this->extra_commission  - $this->deposit - $this->discounted_amount;
	}

	public function calc_total(){
		return $this->total_cost + $this->extra_commission + $this->shipping_country_cost - $this->discounted_amount;
	}

	public function calc_total_for_client(){
		return $this->total_cost + $this->extra_commission + $this->shipping_country_cost  - $this->deposit - $this->discounted_amount;
	}

    
    public function incomes()
    {
        return $this->morphMany(Income::class, 'model');
    }
    
    public function add_income(){ 
        Income::create([ 
            'income_category_id' => 3,
            'entry_date' => date(config('panel.date_format')),
            'amount' => $this->calc_total_for_client(),
            'description' => $this->order_num,
            'model_id' => $this->id,
            'model_type' => 'App\Models\ReceiptSocial',
        ]);
        return 1;
    }
}
