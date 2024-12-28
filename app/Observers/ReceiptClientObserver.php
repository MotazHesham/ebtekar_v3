<?php

namespace App\Observers;

use App\Models\ReceiptClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptClientObserver
{
    
    public function creating(ReceiptClient $receiptClient){
        
        // Getting next Order Num
        $last_receipt_client = ReceiptClient::where('website_setting_id',$receiptClient->website_setting_id)->latest()->first();
        if ($last_receipt_client) {
            $order_num = $last_receipt_client->order_num ? intval(str_replace('#', '', strrchr($last_receipt_client->order_num, "#"))) : 0;
        } else {
            $order_num = 0;
        }
        if($receiptClient->website_setting_id == 2){
            $str = 'ertgal-';
        }elseif($receiptClient->website_setting_id == 3){
            $str = 'figures-';
        }elseif($receiptClient->website_setting_id == 4){
            $str = 'shirti-';
        }elseif($receiptClient->website_setting_id == 5){
            $str = 'martobia-';
        }elseif($receiptClient->website_setting_id == 6){
            $str = 'a1-digital-';
        }elseif($receiptClient->website_setting_id == 7){
            $str = 'ein-';
        }else{ 
            $str = 'ebtekar-';
        }
        $receiptClient->order_num = $str . 'client#' . ($order_num + 1);

        // Assign the Creator Of The Receipt
        $receiptClient->staff_id = Auth::id();

    }

    /**
     * Handle the ReceiptClient "created" event.
     */
    public function created(ReceiptClient $receiptClient): void
    {
        //
    }

    /**
     * Handle the ReceiptClient "updated" event.
     */
    public function updated(ReceiptClient $receiptClient): void
    {
        //
    }

    /**
     * Handle the ReceiptClient "deleted" event.
     */
    public function deleted(ReceiptClient $receiptClient): void
    {
        //
    }

    /**
     * Handle the ReceiptClient "restored" event.
     */
    public function restored(ReceiptClient $receiptClient): void
    {
        //
    }

    /**
     * Handle the ReceiptClient "force deleted" event.
     */
    public function forceDeleted(ReceiptClient $receiptClient): void
    {
        //
    }
}
