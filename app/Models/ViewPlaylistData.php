<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViewPlaylistData extends Model
{
    use HasFactory, SoftDeletes;

    public const PLAYLIST_STATUS_SELECT = [
        'design'     => 'الديزاين',
        'manufacturing'        => 'التصنيع',
        'prepare' => 'التجهيز',
        'shipment'        => 'الارسال للشحن',
        'finish'       => 'التوصيل',
    ];

    public $table = "view_playlist_data";
    
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function getSendToDeliveryDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function getSendToPlaylistDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    } 


}
