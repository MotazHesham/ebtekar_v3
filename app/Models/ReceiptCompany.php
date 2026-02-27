<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Models\WorkflowOperation;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ReceiptCompany extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    protected $appends = [
        'photos',
    ];

    public $table = 'receipt_companies';

    public const PAYMENT_STATUS_SELECT = [
        'unpaid' => 'un Paid',
        'paid'   => 'Paid',
    ];

    public const CLIENT_TYPE_SELECT = [
        'individual' => 'فردي',
        'corporate'  => 'شركة',
    ];

    public static $searchable = [
        'order_num',
        'client_name',
        'phone_number',
        'phone_number_2',
        'description', 
    ];

    protected $dates = [
        'deliver_date',
        'date_of_receiving_order',
        'send_to_playlist_date',
        'send_to_delivery_date',
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
        'deposit',
        'total_cost',
        'calling',
        'quickly',
        'done',
        'no_answer',
        'supplied',
        'client_review',
        'printing_times',
        'deliver_date',
        'date_of_receiving_order',
        'send_to_playlist_date',
        'send_to_delivery_date',
        'done_time', 
        'shipping_country_cost',
        'shipping_address',
        'description',
        'note',
        'cancel_reason',
        'delay_reason',
        'delivery_status',
        'payment_status',
        'playlist_status',
        'staff_id',
        'designer_id',
        'preparer_id',
        'manufacturer_id',
        'shipmenter_id',
        'reviewer_id',
        'delivery_man_id',
        'shipping_country_id',
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

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getDeliverDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDeliverDateAttribute($value)
    {
        $this->attributes['deliver_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getDateOfReceivingOrderAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateOfReceivingOrderAttribute($value)
    {
        $this->attributes['date_of_receiving_order'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getSendToPlaylistDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setSendToPlaylistDateAttribute($value)
    {
        $this->attributes['send_to_playlist_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getSendToDeliveryDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setSendToDeliveryDateAttribute($value)
    {
        $this->attributes['send_to_delivery_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getDoneTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDoneTimeAttribute($value)
    {
        $this->attributes['done_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getPhotosAttribute()
    {
        $files = $this->getMedia('photos');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
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

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id')->withTrashed();
    }

    public function delivery_man()
    {
        return $this->belongsTo(User::class, 'delivery_man_id')->withTrashed();
    }

    public function shipping_country()
    {
        return $this->belongsTo(Country::class, 'shipping_country_id')->withTrashed();
    }

	// operations 
	public function calc_total_for_delivery(){
		return $this->total_cost  - $this->deposit;
	}

	public function calc_total(){
		return $this->total_cost + $this->shipping_country_cost;
	}

	public function calc_total_for_client(){
		return $this->total_cost + $this->shipping_country_cost  - $this->deposit;
	}

    public function workflowOperations()
    {
        return $this->morphMany(WorkflowOperation::class, 'model');
    }
}
