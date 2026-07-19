<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\SendSmsService;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $from;
    protected $text;

    /**
     * Create a new job instance.
     *
     * @param string $to
     * @param string $from
     * @param string $text
     * @return void
     */
    public function __construct($to, $from, $text)
    {
        $this->to = $to;
        $this->from = $from;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!settingEnabled('sms_enabled')) {
            loggerAction('sms', 'info', 'SMS skipped (disabled in settings)', [
                'to' => $this->to,
                'from' => $this->from,
                'text' => $this->text,
            ]);

            return;
        }

        $smsService = new SendSmsService();
        $smsService->sendSMS($this->to, $this->from, $this->text);
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        loggerAction('sms', 'error', 'SMS sending failed: ' . $exception->getMessage(), [
            'to' => $this->to,
            'from' => $this->from,
            'text' => $this->text,
            'exception' => $exception
        ]);
    }
}
