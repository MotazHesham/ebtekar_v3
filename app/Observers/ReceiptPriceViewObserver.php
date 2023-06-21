<?php

namespace App\Observers;

use App\Models\ReceiptPriceView;
use Illuminate\Support\Facades\Auth;

class ReceiptPriceViewObserver
{
    public function creating(ReceiptPriceView $receiptPriceView){
        
        // Getting next Order Num
        $last_receipt_price_view = ReceiptPriceView::latest()->first();
        if ($last_receipt_price_view) {
            $order_num = $last_receipt_price_view->order_num ? intval(str_replace('#', '', strrchr($last_receipt_price_view->order_num, "#"))) : 0;
        } else {
            $order_num = 0;
        }
        $receiptPriceView->order_num = 'receipt-price-view#' . ($order_num + 1);

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
