<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class Zone extends Model
{
    use Auditable, HasFactory;

    public $table = 'zones';

    protected $dates = [
        'created_at',
        'updated_at', 
    ]; 

    protected $fillable = [
        'name',
        'delivery_cost',
        'created_at',
        'updated_at', 
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class,'zone_country_pivot','zone_id','country_id');
    }
}
