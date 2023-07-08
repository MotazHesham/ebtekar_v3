<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockupStock extends Model
{
    use HasFactory;

    public $table = 'mockup_stocks'; 

    protected $dates = [
        'created_at',
        'updated_at', 
    ];

    protected $fillable = [
        'variant',
        'price',
        'quantity',
        'mockup_id',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function mockup()
    {
        return $this->belongsTo(Mockup::class,'mockup_id');
    }
}
