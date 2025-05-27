<?php

namespace App\Models;

use DateTimeInterface; 
use Illuminate\Database\Eloquent\Model; 

class OrderNumberCounter extends Model
{ 

    public $table = 'order_number_counters'; 

    const TYPE_SELECT = [
        'social#' => 'Receipt Social', 
        'branch#' => 'Receipt Branch',
        'client#' => 'Receipt Client',
        'receipt-company#' => 'Receipt Company',
        'receipt-outgoings#' => 'Receipt Outgoing',
        'price-view#' => 'Receipt Price View',
        'seller#' => 'Seller Orders',
        'customer#' => 'Customer Orders',
    ];

    const PREFIX_SELECT = [
        'ertgal-' => 'ertgal',  
        'figures-' => 'figures',
        'novi-' => 'novi',
        'martobia-' => 'martobia',
        'a1-digital-' => 'a1-digital',
        'ein-' => 'ein',
        'ebtekar-' => 'ebtekar',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'type',
        'prefix',
        'last_number', 
        'created_at',
        'updated_at', 
    ]; 
}
