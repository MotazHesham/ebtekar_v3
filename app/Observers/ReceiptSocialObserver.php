<?php

namespace App\Observers;

use App\Models\Country;
use App\Models\ReceiptSocial;
use Illuminate\Support\Facades\Auth;

class ReceiptSocialObserver
{
    public function creating(ReceiptSocial $receiptSocial){
        
        // Getting next Order Num
        $last_receipt_social = ReceiptSocial::where('website_setting_id',$receiptSocial->website_setting_id)->latest()->first();
        if ($last_receipt_social) {
            $order_num = $last_receipt_social->order_num ? intval(str_replace('#', '', strrchr($last_receipt_social->order_num, "#"))) : 0;
        } else {
            $order_num = 0;
        } 
        if($receiptSocial->website_setting_id == 2){
            $str = 'ertgal-';
        }elseif($receiptSocial->website_setting_id == 3){
            $str = 'figures-';
        }elseif($receiptSocial->website_setting_id == 4){
            $str = 'shirti-';
        }elseif($receiptSocial->website_setting_id == 5){
            $str = 'martobia-';
        }else{ 
            $str = 'ebtekar-';
        }
        $receiptSocial->order_num = $str . 'social#' . ($order_num + 1);

        // Assign the Creator Of The Receipt
        $receiptSocial->staff_id = Auth::id(); 

        // Get The Cost of the Shipping Country
        $country = Country::findOrFail($receiptSocial->shipping_country_id); 
        $receiptSocial->shipping_country_cost = $country->cost; 
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
