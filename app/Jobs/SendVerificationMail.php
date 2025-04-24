<?php

namespace App\Jobs;

use App\Mail\ConfirmedOrderMail;
use App\Mail\VerifyUserMail;
use App\Models\Order;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendVerificationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */ 

    public function __construct(public User $user,public WebsiteSetting $site_settings,public string $email)
    {  
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new VerifyUserMail($this->user,$this->site_settings)); 
    }
}
