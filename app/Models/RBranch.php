<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RBranch extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'r_branches';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const PAYMENT_TYPE_SELECT = [
        'cash'        => 'كاش',
        'parts'       => 'دفعات',
        'permissions' => 'أذونات',
    ];

    protected $fillable = [
        'name',
        'phone_number',
        'remaining',
        'payment_type',
        'r_client_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function r_client()
    {
        return $this->belongsTo(RClient::class, 'r_client_id');
    } 

    public function qr_products_rbranch(){
        return $this->hasMany(QrProductRBranch::class, 'r_branch_id');
    }

    public function qr_scan_history(){
        return $this->hasMany(QrScanHistory::class, 'r_branch_id');
    }

    public function incomes()
    {
        return $this->morphMany(Income::class, 'model');
    } 
}
