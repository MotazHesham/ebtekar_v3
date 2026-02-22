<?php

namespace Modules\Ads\Console\Commands;

use App\Jobs\RecalculateAdsAccountHistory;
use App\Models\CombinedOrder;
use Illuminate\Console\Command;

class ReCalculateAdsHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:re-calculate-ads-history';

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
        $combinedOrders = CombinedOrder::whereNotNull('ad_history_id')->get();
        foreach ($combinedOrders as $combinedOrder) {
            if($combinedOrder->adHistory){
                RecalculateAdsAccountHistory::dispatch($combinedOrder->adHistory);
            }
        }
    }
}
