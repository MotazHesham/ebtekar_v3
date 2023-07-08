<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Design extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'designs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'pending' => 'قيد النتظار',
        'accepted'  => 'تمت الموافقة',
        'refused' => 'مرفوض',
    ];

    protected $fillable = [
        'design_name',
        'profit',
        'colors',
        'dataset_1',
        'dataset_2',
        'dataset_3',
        'status',
        'cancel_reason',
        'user_id',
        'mockup_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mockup()
    {
        return $this->belongsTo(Mockup::class, 'mockup_id');
    }

    public function design_images()
    {
        return $this->hasMany(DesignImage::class, 'design_id');
    }

    public function product(){
        return $this->hasOne(Product::class, 'design_id');
    }
}
