<?php

namespace App\Observers;

use App\Models\RBranch;
use App\Models\ReceiptBranch;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

class ReceiptBranchObserver
{
    
    public function creating(ReceiptBranch $receiptBranch){
        
        DB::transaction(function () use ($receiptBranch) {
            // Getting next Order Num
            $last_receipt_branch = ReceiptBranch::where('website_setting_id',$receiptBranch->website_setting_id)->lockForUpdate()->latest()->first();
            if ($last_receipt_branch) {
                $order_num = $last_receipt_branch->order_num ? intval(str_replace('#', '', strrchr($last_receipt_branch->order_num, "#"))) : 0;
            } else {
                $order_num = 0;
            }
            if($receiptBranch->website_setting_id == 2){
                $str = 'ertgal-';
            }elseif($receiptBranch->website_setting_id == 3){
                $str = 'figures-';
            }elseif($receiptBranch->website_setting_id == 4){
                $str = 'shirti-';
            }elseif($receiptBranch->website_setting_id == 5){
                $str = 'martobia-';
            }else{ 
                $str = 'ebtekar-';
            }
            $receiptBranch->order_num = $str . 'branch#' . ($order_num + 1);

            // Assign the Creator Of The Receipt
            $receiptBranch->staff_id = Auth::id();  

            // Assign the branch name and phone 
            $branch = RBranch::find($receiptBranch->r_branch_id);
            $receiptBranch->client_name = $branch->name;
            $receiptBranch->phone_number = $branch->phone_number;
        });

    }

    public function updating(ReceiptBranch $receiptBranch){ 
        // Assign the branch name and phone 
        $branch = RBranch::find($receiptBranch->r_branch_id);
        if($branch){
            $receiptBranch->client_name = $branch->name;
            $receiptBranch->phone_number = $branch->phone_number;
        }
    }
    /**
     * Handle the ReceiptBranch "created" event.
     */
    public function created(ReceiptBranch $receiptBranch): void
    {
        //
    }

    /**
     * Handle the ReceiptBranch "updated" event.
     */
    public function updated(ReceiptBranch $receiptBranch): void
    {
        //
    }

    /**
     * Handle the ReceiptBranch "deleted" event.
     */
    public function deleted(ReceiptBranch $receiptBranch): void
    {
        //
    }

    /**
     * Handle the ReceiptBranch "restored" event.
     */
    public function restored(ReceiptBranch $receiptBranch): void
    {
        //
    }

    /**
     * Handle the ReceiptBranch "force deleted" event.
     */
    public function forceDeleted(ReceiptBranch $receiptBranch): void
    {
        //
    }
}
