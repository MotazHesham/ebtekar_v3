<?php

namespace App\Jobs;

use App\Models\WebsiteSetting;
use App\Services\FacebookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFacebookEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userData;
    protected $contentData;
    protected $siteSettingsId;
    protected $eventType;

    public function __construct(array $contentData, $siteSettingsId, array $userData = [],$eventType)
    {
        $this->contentData = $contentData;
        $this->siteSettingsId = $siteSettingsId;
        $this->userData = $userData;
        $this->eventType = $eventType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $siteSettings = WebsiteSetting::find($this->siteSettingsId);

        if (!$siteSettings || !$siteSettings->fb_pixel_id || !$siteSettings->fb_access_token) {
            return;
        }

        $facebookService = new FacebookService($siteSettings);
        if($this->eventType == 'all'){
            $facebookService->sendEventFromServer($this->contentData,$this->userData);
        }elseif($this->eventType == 'search'){  
            $facebookService->sendEventSearch( $this->contentData,$this->userData); 
        }elseif($this->eventType == 'pageview'){
            $facebookService->sendEventPageView( $this->contentData,$this->userData);  
        }
    }
}
