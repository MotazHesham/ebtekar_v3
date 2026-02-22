<?php

namespace App\Console\Commands;

use App\Jobs\RecalculateAdsAccountHistory;
use App\Models\CombinedOrder;
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
        $combinedOrders = CombinedOrder::where('ad_history_id', null)
            ->with('marketPlace')
            ->whereNotNull('utm_details')->get();

        $count = 0;
        foreach ($combinedOrders as $combinedOrder) { 
            if(!$combinedOrder->created_at){
                continue;
            }
            $date = explode(' ', $combinedOrder->created_at)[0];
            if($combinedOrder->marketPlace->platform_type == 'easy_order'){
                $utmDetails = $combinedOrder->utm_details ? json_decode($combinedOrder->utm_details, true) : null;
                $adHistory = getAdHistoryByUtm('easy_orders',$utmDetails, $date);
                $combinedOrder->ad_history_id = $adHistory->id ?? null;
                $combinedOrder->save();
                if($adHistory){ 
                    RecalculateAdsAccountHistory::dispatch($adHistory);
                }
            }
            $count++;
        }
        $this->info("Assigned ad_history_id to {$count} combined orders.");
        return 0;
    }
}
