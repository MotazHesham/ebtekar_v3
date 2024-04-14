<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrProductRBranch extends Model
{
    public $table = 'qr_product_rbranches';
    
    protected $dates = [
        'created_at',
        'updated_at', 
    ];


    protected $fillable = [
        'qr_product_id', 
        'r_branch_id',   
        'quantity',
        'names',
        'created_at',
        'updated_at',
    ]; 

    public function branch()
    {
        return $this->belongsTo(RBranch::class, 'r_branch_id');
    }
    public function product()
    {
        return $this->belongsTo(QrProduct::class, 'qr_product_id');
    }
}
