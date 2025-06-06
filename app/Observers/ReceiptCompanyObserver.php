<?php

namespace App\Observers;

use App\Models\Country;
use App\Models\ReceiptCompany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptCompanyObserver
{
    public function creating(ReceiptCompany $receiptComapny){ 
        $receiptComapny->order_num = generateOrderNumber('receipt-company#');
        // Assign the Creator Of The Receipt
        $receiptComapny->staff_id = Auth::check() ? Auth::id() : null;  

        // Get The Cost of the Shipping Country
        if($receiptComapny->shipping_country_id){
            $country = Country::findOrFail($receiptComapny->shipping_country_id); 
            $receiptComapny->shipping_country_cost = $country->cost;  
        }

    }

    public function updating(ReceiptCompany $receiptComapny){ 
        // Get The Cost of the Shipping Country
        $country = Country::find($receiptComapny->shipping_country_id); 
        if($country){
            $receiptComapny->shipping_country_cost = $country->cost; 
        }
    }


    /**
     * Handle the ReceiptCompany "created" event.
     */
    public function created(ReceiptCompany $receiptCompany): void
    {
        //
    }

    /**
     * Handle the ReceiptCompany "updated" event.
     */
    public function updated(ReceiptCompany $receiptCompany): void
    {
        //
    }

    /**
     * Handle the ReceiptCompany "deleted" event.
     */
    public function deleted(ReceiptCompany $receiptCompany): void
    {
        //
    }

    /**
     * Handle the ReceiptCompany "restored" event.
     */
    public function restored(ReceiptCompany $receiptCompany): void
    {
        //
    }

    /**
     * Handle the ReceiptCompany "force deleted" event.
     */
    public function forceDeleted(ReceiptCompany $receiptCompany): void
    {
        //
    }
}
