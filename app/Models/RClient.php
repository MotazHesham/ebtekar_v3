<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RClient extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'r_clients';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const MANAGE_TYPE_SELECT = [
        'seperate' => 'إدارات منفصلة',
        'unified'  => 'إدارة موحدة',
    ];

    protected $fillable = [
        'name',
        'phone_number',
        'remaining',
        'manage_type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function rClientRBranches()
    {
        return $this->hasMany(RBranch::class, 'r_client_id', 'id');
    }

    public function incomes()
    {
        return $this->morphMany(Income::class, 'model');
    } 
}
