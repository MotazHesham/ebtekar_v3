<?php

namespace App\Observers;

use App\Models\Country;
use Illuminate\Support\Facades\Cache;

class CountryObserver
{
    public function creating(Country $country){
        Cache::forget('countries');
    }
    public function updating(Country $country){
        Cache::forget('countries');
    } 

    public function deleting(Country $country){
        Cache::forget('countries');
    } 
}