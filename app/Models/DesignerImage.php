<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignerImage extends Model
{
    use HasFactory;
    
    public $table = 'designer_images'; 

    protected $dates = [
        'created_at',
        'updated_at', 
    ];

    protected $fillable = [ 
        'image',
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
