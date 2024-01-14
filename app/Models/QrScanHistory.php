<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrScanHistory extends Model
{
    public $table = 'qr_scan_history';
    
    protected $dates = [
        'created_at',
        'updated_at', 
    ];


    protected $fillable = [
        'name',
        'scanned',
        'results',  
        'quantity',  
        'r_branch_id',  
        'created_at',
        'updated_at',
    ]; 
    
    public function branch()
    {
        return $this->belongsTo(RBranch::class, 'r_branch_id');
    }
    

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }
}
