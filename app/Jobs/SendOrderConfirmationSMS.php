<?php

namespace App\Jobs;

use App\Mail\ConfirmedOrderMail;
use App\Models\Order;
use App\Models\WebsiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $phone_number; 

    public function __construct(public Order $order,public WebsiteSetting $site_settings,$phone_number)
    { 
        $this->phone_number = $phone_number;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        
        $Token = 'EAAFeh4fpqfsBO7Pwhd8Dxxbxtdx9qskUz8YiF9XZBrkZCZCEVDgCrZAg4YjpkQRrxMQEDeftiQcQ4soQXYyZC9MXJuA6Mvwm4tskTdsWTXJul7YbqIRjMKfD42bMxK5jaaOaVkMEiknpeNOC0ru6Vim3dNW2ymsmUpskMkafkUyBtThxLL8I0Wtjd3qiJDyEAnWyo5KBRYsgSmSrXj4LpQNJ0S1L8laVfyuYZD'; 
        $data = [
            "messaging_product" => "whatsapp",
            "to" => "20" . $this->order->phone_number,
            "type" => "template",
            "template" => [
                "name" => "order_checkout",
                "language" => [
                    "code" => "ar"
                ],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => [
                            [
                                "type" => "text",
                                "text" => $this->order->client_name
                            ],
                            [
                                "type" => "text",
                                "text" => $this->order->order_num
                            ],
                            [
                                "type" => "text",
                                "text" => $this->order->calc_total_for_client()
                            ],
                            [
                                "type" => "text",
                                "text" => "Ebtekar Store"
                            ],
                        ]
                    ]
                ] 
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: Bearer ' . config('app.whatsapp_token'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v18.0/'.config('app.whatsapp_phone_id').'/messages');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        
        return $response;
    }
}
