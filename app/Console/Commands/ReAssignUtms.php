<?php

namespace App\Console\Commands;

use App\Jobs\RecalculateAdsAccountHistory; 
use App\Models\ReceiptSocial;
use Carbon\Carbon;
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
            $createdAt = $receiptSocial->created_at ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $receiptSocial->created_at)->format('Y-m-d H:i:s') : null;
            if(!$receiptSocial->created_at){
                continue;
            }
            $date = explode(' ', $createdAt)[0]; 
            $adHistory = getAdHistoryByUtm('shopify',$receiptSocial->utm_details, $date);
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
