<?php

namespace App\Observers;

use App\Models\Country;
use App\Models\ReceiptSocial;
use Illuminate\Support\Facades\Auth;

class ReceiptSocialObserver
{
    public function creating(ReceiptSocial $receiptSocial){
        
        // Getting next Order Num
        $last_receipt_social = ReceiptSocial::latest()->first();
        if ($last_receipt_social) {
            $order_num = $last_receipt_social->order_num ? intval(str_replace('#', '', strrchr($last_receipt_social->order_num, "#"))) : 0;
        } else {
            $order_num = 0;
        }
        $receiptSocial->order_num = 'receipt-social#' . ($order_num + 1);

        // Assign the Creator Of The Receipt
        $receiptSocial->staff_id = Auth::id(); 

        // Get The Cost of the Shipping Country
        $country = Country::findOrFail($receiptSocial->shipping_country_id); 
        $receiptSocial->shipping_country_cost = $country->cost; 
    }

    public function updating(ReceiptSocial $receiptSocial){ 
        // Get The Cost of the Shipping Country
        $country = Country::findOrFail($receiptSocial->shipping_country_id); 
        $receiptSocial->shipping_country_cost = $country->cost; 
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
