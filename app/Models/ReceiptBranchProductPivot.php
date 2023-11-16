<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface; 
use Illuminate\Database\Eloquent\Model;  

class ReceiptBranchProductPivot extends Model
{
    use Auditable; 
    
    public static $searchable = [
        'description',
    ];

    public $table = 'receipt_branch_receipt_branch_product';

    protected $dates = [
        'created_at',
        'updated_at', 
    ];

    protected $fillable = [ 
        'description',
        'quantity',
        'price',
        'total_cost',
        'receipt_branch_id',
        'receipt_branch_product_id',
        'created_at',
        'updated_at', 
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    } 

    public function receipts()
    {
        return $this->belongsTo(ReceiptBranch::class,'receipt_branch_id');
    }

    public function products()
    {
        return $this->belongsTo(ReceiptBranchProduct::class,'receipt_branch_product_id')->withTrashed();
    }
}
