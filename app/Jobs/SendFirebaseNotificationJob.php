<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Google\Client as GoogleClient;

class SendFirebaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $userType;
    protected $topic;
    /**
     * Create a new job instance.
     *
     * @param array $data
     * @param string $userType
     * @param string $topic
     * @return void
     */
    public function __construct($data, $userType, $topic = null)
    {
        $this->data = $data;
        $this->userType = $userType;
        $this->topic = $topic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendFirebaseNotification();
    }

    /**
     * Send Firebase notification
     */
    protected function sendFirebaseNotification()
    {
        if (!settingEnabled('push_notifications_enabled')) {
            return;
        }

        $url = null;
        $credentialsFilePath = null;
        if ($this->userType === 'customer') {
            $url = config('services.firebase.url');
            $credentialsFilePath = base_path(config('services.firebase.credentials_file'));
        }

        if (!$url || !$credentialsFilePath) {
            loggerAction('firebase', 'error', 'Firebase URL or credentials file not found', [
                'url' => $url,
                'credentials_file' => $credentialsFilePath
            ]);
            return;
        }

        try {
            $client = new GoogleClient();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope("https://www.googleapis.com/auth/firebase.messaging");
            $client->fetchAccessTokenWithAssertion();
            $token = $client->getAccessToken();

            $access_token = $token['access_token'];

            $headers = [
                'Authorization: Bearer ' . $access_token,
                'Content-Type: application/json'
            ];

            // Firebase FCM v1 requires data to be a flat key-value map with string values
            $dataFields = [
                'item_type' => $this->data['type'],
                'item_type_id' => (string)$this->data['id'],
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ];

            // Decode and flatten additional data, ensuring all values are strings
            if (!empty($this->data['data'])) {
                $additionalData = json_decode($this->data['data'], true) ?? [];
                foreach ($additionalData as $key => $value) {
                    $dataFields[$key] = (string)$value;
                }
            }

            $fields = array(
                'message' => [
                    'notification' => [
                        'title' => $this->data['title'],
                        'body' => $this->data['text']
                    ],
                    'data' => $dataFields
                ]
            );

            if ($this->topic) {
                $fields['message']['topic'] = $this->topic;
            } else {
                $fields['message']['token'] = $this->data['device_token'];
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            $result = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            loggerAction('firebase', 'info', 'Firebase Sent', [
                'status' => $http_code,
                'response' => json_decode($result, true)
            ]);
        } catch (\Exception $e) {
            loggerAction('firebase', 'error', 'Firebase Exception: ' . $e->getMessage(), $e->getTrace());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        loggerAction('firebase', 'error', 'Firebase notification failed: ' . $exception->getMessage(), [
            'data' => $this->data,
            'notification_type' => $this->userType,
            'exception' => $exception
        ]);
    }
}
