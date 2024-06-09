<?php

namespace App\Observers;

use App\Models\ReceiptOutgoing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptOutgoingObserver
{
    
    public function creating(ReceiptOutgoing $receiptOutgoing){
    
        // Getting next Order Num
        $last_receipt_outgoing = ReceiptOutgoing::latest()->first();
        if ($last_receipt_outgoing) {
            $order_num = $last_receipt_outgoing->order_num ? intval(str_replace('#', '', strrchr($last_receipt_outgoing->order_num, "#"))) : 0;
        } else {
            $order_num = 0;
        }
        $receiptOutgoing->order_num = 'receipt-outgoings#' . ($order_num + 1);

        // Assign the Creator Of The Receipt
        $receiptOutgoing->staff_id = Auth::id(); 
    }

    /**
     * Handle the ReceiptOutgoing "created" event.
     */
    public function created(ReceiptOutgoing $receiptOutgoing): void
    {
        //
    }

    /**
     * Handle the ReceiptOutgoing "updated" event.
     */
    public function updated(ReceiptOutgoing $receiptOutgoing): void
    {
        //
    }

    /**
     * Handle the ReceiptOutgoing "deleted" event.
     */
    public function deleted(ReceiptOutgoing $receiptOutgoing): void
    {
        //
    }

    /**
     * Handle the ReceiptOutgoing "restored" event.
     */
    public function restored(ReceiptOutgoing $receiptOutgoing): void
    {
        //
    }

    /**
     * Handle the ReceiptOutgoing "force deleted" event.
     */
    public function forceDeleted(ReceiptOutgoing $receiptOutgoing): void
    {
        //
    }
}
