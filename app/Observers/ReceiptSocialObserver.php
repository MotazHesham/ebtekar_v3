<?php

namespace App\Observers;

use App\Models\Country;
use App\Models\ReceiptSocial;
use Illuminate\Support\Facades\Auth; 


class ReceiptSocialObserver
{
    public function creating(ReceiptSocial $receiptSocial){
        
        $receiptSocial->order_num = generateOrderNumber('social#',$receiptSocial->website_setting_id);

        // Assign the Creator Of The Receipt
        $receiptSocial->staff_id = Auth::check() ? Auth::id() : null;  

        // Get The Cost of the Shipping Country
        if($receiptSocial->shipping_country_id){
            $country = Country::findOrFail($receiptSocial->shipping_country_id); 
            $receiptSocial->shipping_country_cost = $country->cost; 
        }
    }

    public function updating(ReceiptSocial $receiptSocial){ 
        // Get The Cost of the Shipping Country
        $country = Country::find($receiptSocial->shipping_country_id); 
        if($country){
            $receiptSocial->shipping_country_cost = $country->cost; 
        }
    }

    /**
     * Handle the ReceiptSocial "created" event.
     */
    public function created(ReceiptSocial $receiptSocial): void
    {
        //
    }

    /**
     * Handle the ReceiptSocial "updated" event.
     */
    public function updated(ReceiptSocial $receiptSocial): void
    {
        //
    }

    /**
     * Handle the ReceiptSocial "deleted" event.
     */
    public function deleted(ReceiptSocial $receiptSocial): void
    {
        //
    }

    /**
     * Handle the ReceiptSocial "restored" event.
     */
    public function restored(ReceiptSocial $receiptSocial): void
    {
        //
    }

    /**
     * Handle the ReceiptSocial "force deleted" event.
     */
    public function forceDeleted(ReceiptSocial $receiptSocial): void
    {
        //
    }
}
