<?php

namespace App\Observers;

use App\Models\ReceiptClient;
use Illuminate\Support\Facades\Auth;

class ReceiptClientObserver
{
    
    public function creating(ReceiptClient $receiptClient){
        
        // Getting next Order Num
        $last_receipt_client = ReceiptClient::latest()->first();
        if ($last_receipt_client) {
            $order_num = $last_receipt_client->order_num ? intval(str_replace('#', '', strrchr($last_receipt_client->order_num, "#"))) : 0;
        } else {
            $order_num = 0;
        }
        $receiptClient->order_num = 'receipt-client#' . ($order_num + 1);

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
