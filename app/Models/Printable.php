<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printable extends Model
{ 
    use HasFactory;

    public $table = 'printable'; 

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'printable_model',
        'user_id',
        'printable_id',
        'created_at',
        'updated_at', 
    ];
}
