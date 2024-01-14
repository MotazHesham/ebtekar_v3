<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrProduct extends Model
{
    public $table = 'qr_products';
    
    protected $dates = [
        'created_at',
        'updated_at', 
    ];


    protected $fillable = [
        'product',
        'quantity',
        'r_branch_id',  
        'created_at',
        'updated_at',
    ]; 
    
    public function branch()
    {
        return $this->belongsTo(RBranch::class, 'r_branch_id');
    }

    public function names()
    {
        return $this->hasMany(QrProductKey::class, 'qr_product_id');
    }
}
