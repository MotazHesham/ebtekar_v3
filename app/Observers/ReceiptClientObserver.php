<?php

namespace App\Observers;

use App\Models\ReceiptClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptClientObserver
{
    
    public function creating(ReceiptClient $receiptClient){ 
        $receiptClient->order_num = generateOrderNumber('client#',$receiptClient->website_setting_id);

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
