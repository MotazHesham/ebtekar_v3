<?php

namespace App\Jobs;

use App\Http\Controllers\PushNotificationController;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tokens;
    private $title;
    private $body;
    private $link;
    private $site_settings;
    /**
     * Create a new job instance.
     */
    public function __construct($title,$body,$tokens,$link,$site_settings)
    {
        $this->tokens = $tokens;
        $this->title = $title;
        $this->body = $body;
        $this->link = $link;
        $this->site_settings = $site_settings;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    { 
        $push_controller = new PushNotificationController();
        $push_controller->sendNotification($this->title, $this->body, $this->tokens,$this->link,$this->site_settings);
    }
}
