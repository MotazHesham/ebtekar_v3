<?php

namespace App\Observers;

use App\Models\ReceiptPriceView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptPriceViewObserver
{
    public function creating(ReceiptPriceView $receiptPriceView){
        
        // Getting next Order Num
        $last_receipt_price_view = ReceiptPriceView::where('website_setting_id',$receiptPriceView->website_setting_id)->latest()->first();
        if ($last_receipt_price_view) {
            $order_num = $last_receipt_price_view->order_num ? intval(str_replace('#', '', strrchr($last_receipt_price_view->order_num, "#"))) : 0;
        } else {
            $order_num = 0;
        }
        if($receiptPriceView->website_setting_id == 2){
            $str = 'ertgal-';
        }elseif($receiptPriceView->website_setting_id == 3){
            $str = 'figures-';
        }elseif($receiptPriceView->website_setting_id == 4){
            $str = 'novi-';
        }elseif($receiptPriceView->website_setting_id == 5){
            $str = 'martobia-';
        }elseif($receiptPriceView->website_setting_id == 6){
            $str = 'a1-digital-';
        }elseif($receiptPriceView->website_setting_id == 7){
            $str = 'ein-';
        }else{ 
            $str = 'ebtekar-';
        }
        $receiptPriceView->order_num = $str . 'price-view#' . ($order_num + 1);

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
