<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    use HasFactory;

    public $table = 'searches'; 

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [ 
        'search',
        'count',
        'created_at',
        'updated_at', 
    ];
}
