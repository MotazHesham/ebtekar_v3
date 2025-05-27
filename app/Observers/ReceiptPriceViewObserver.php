<?php

namespace App\Observers;

use App\Models\ReceiptPriceView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptPriceViewObserver
{
    public function creating(ReceiptPriceView $receiptPriceView){ 
        $receiptPriceView->order_num = generateOrderNumber('price-view#',$receiptPriceView->website_setting_id);
        // Assign the Creator Of The Receipt
        $receiptPriceView->staff_id = Auth::id(); 
    }

    /**
     * Handle the ReceiptPriceView "created" event.
     */
    public function created(ReceiptPriceView $receiptPriceView): void
    {
        //
    }

    /**
     * Handle the ReceiptPriceView "updated" event.
     */
    public function updated(ReceiptPriceView $receiptPriceView): void
    {
        //
    }

    /**
     * Handle the ReceiptPriceView "deleted" event.
     */
    public function deleted(ReceiptPriceView $receiptPriceView): void
    {
        //
    }

    /**
     * Handle the ReceiptPriceView "restored" event.
     */
    public function restored(ReceiptPriceView $receiptPriceView): void
    {
        //
    }

    /**
     * Handle the ReceiptPriceView "force deleted" event.
     */
    public function forceDeleted(ReceiptPriceView $receiptPriceView): void
    {
        //
    }
}
