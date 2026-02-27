<?php

namespace App\Console\Commands;

use App\Jobs\RecalculateAdsAccountHistory; 
use App\Models\ReceiptSocial;
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
        $receiptSocials = ReceiptSocial::whereNotNull('ad_history_id')->with('adHistory')->get();
        foreach ($receiptSocials as $receiptSocial) {
            if($receiptSocial->adHistory){
                RecalculateAdsAccountHistory::dispatch($receiptSocial->adHistory);
                $this->info("Recalculated ad history for receipt social {$receiptSocial->id}");
            }
        }
    }
}
