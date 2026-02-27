<?php

namespace App\Console\Commands;

use App\Jobs\RecalculateAdsAccountHistory; 
use App\Models\ReceiptSocial;
use Illuminate\Console\Command;

class ReAssignUtms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:re-assign-utms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $receiptSocials = ReceiptSocial::where('ad_history_id', null)       
            ->whereNotNull('utm_details')->get();

        $count = 0;
        foreach ($receiptSocials as $receiptSocial) { 
            if(!$receiptSocial->created_at){
                continue;
            }
            $date = explode(' ', $receiptSocial->created_at)[0];
            $utmDetails = $receiptSocial->utm_details ? json_decode($receiptSocial->utm_details, true) : null;
            $adHistory = getAdHistoryByUtm('shopify',$utmDetails, $date);
            $receiptSocial->ad_history_id = $adHistory->id ?? null;
            $receiptSocial->save();
            if($adHistory){ 
                RecalculateAdsAccountHistory::dispatch($adHistory);
            }
            $count++;
        }
        $this->info("Assigned ad_history_id to {$count} receipt socials.");
        return 0;
    }
}
