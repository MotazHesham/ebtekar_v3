<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrProductKey extends Model
{
    public $table = 'qr_product_keys';
    
    protected $dates = [
        'created_at',
        'updated_at', 
    ];


    protected $fillable = [
        'name',
        'qr_product_id',  
        'created_at',
        'updated_at',
    ]; 
    
    public function product()
    {
        return $this->belongsTo(QrProduct::class, 'qr_product_id');
    }
}
