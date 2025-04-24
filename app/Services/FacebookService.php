<?php

namespace App\Services;

use FacebookAds\Api;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class FacebookService
{
    protected $pixelId;
    protected $accessToken;
    protected $testEventCode;

    public function __construct()
    {
        $this->pixelId = config('facebook.pixel_id');
        $this->accessToken = config('facebook.access_token');
        if (empty($this->pixelId) || empty($this->accessToken)) {
            throw new \RuntimeException('Facebook Pixel ID or Access Token not configured');
        }
        $this->testEventCode = config('facebook.test_event_code');

        // Initialize the Facebook SDK
        Api::init(null, null, $this->accessToken);
    }

    public function sendEventFromJs($eventName, $userData, $contentData)
    {
        $event = $this->createEvent($eventName, $userData, $contentData);
        $this->sendEvent($event,$eventName);
    }

    public function sendViewContentEvent($userData, $contentData)
    {
        $event = $this->createEvent('ViewContent', $userData, $contentData);
        $this->sendEvent($event,'ViewContent');
    }

    protected function createEvent($eventName, $userData, $contentData)
    {
        // Create UserData with enhanced matching parameters
        $userDataObj = (new UserData())
            ->setClientIpAddress(request()->ip())
            ->setClientUserAgent(request()->userAgent())
            ->setFbp($this->validateFbp($this->getFbpFromCookie($userData['fbp'] ?? null)))
            ->setFbc($this->validateFbc($this->getFbcFromCookie($userData['fbc'] ?? null)))
            ->setExternalId($userData['external_id'] ?? null)
            ->setEmail($userData['email'] ?? null)
            ->setPhone($userData['phone'] ?? null);

        // Validate content_ids is an array of strings
        if (isset($contentData['content_ids'])) {
            $contentData['content_ids'] = array_map('strval', (array)$contentData['content_ids']);
        }

        // Create CustomData
        $customData = (new CustomData())
            ->setContentName($contentData['content_name'] ?? null)
            ->setContentIds($contentData['content_ids'] ?? null)
            ->setContentType($contentData['content_type'] ?? null) 
            ->setContentCategory($this->validateContentCategory($contentData['content_category'] ?? null))
            ->setValue($this->validateValue($contentData['value'] ?? null))
            ->setCurrency($this->validateCurrency($contentData['currency'] ?? 'USD'));

        // Create Event
        return (new Event())
            ->setEventName($eventName)
            ->setEventTime(time())
            ->setEventSourceUrl(request()->fullUrl())
            ->setUserData($userDataObj)
            ->setCustomData($customData)
            ->setActionSource(ActionSource::WEBSITE);
    }

    protected function sendEvent($event, $eventName)
    {
        $events = [$event];
        $request = (new EventRequest($this->pixelId))
            ->setEvents($events);

        if ($this->testEventCode) {
            $request->setTestEventCode($this->testEventCode);
        }

        try {
            $response = $request->execute();
            Log::info('Facebook CAPI Success: eventName => ' . $eventName, (array)$response);
        } catch (\Exception $e) {
            Log::error('Facebook CAPI Failed: eventName => ' . $eventName, ['error' => $e]);
        }
    }

    /**
     * Get Facebook Browser ID from cookie with fallback
     */
    protected function getFbpFromCookie($default = null)
    {
        return Cookie::get('_fbp') ?? $default;
    }

    /**
     * Get Facebook Click ID from cookie with fallback
     */
    protected function getFbcFromCookie($default = null)
    {
        return Cookie::get('_fbc') ?? $default;
    }
    
    // Add these validation methods
    protected function validateFbp($fbp)
    {
        return $fbp && preg_match('/^fb\.\d+\.\d+\.\d+$/', $fbp) ? $fbp : null;
    }

    protected function validateFbc($fbc)
    {
        return $fbc && preg_match('/^fb\.\d+\.\d+\.\d+$/', $fbc) ? $fbc : null;
    }

    protected function validateValue($value)
    {
        return is_numeric($value) ? (float)$value : null;
    }

    protected function validateCurrency($currency)
    {
        return strlen($currency) === 3 ? $currency : 'USD';
    }
    protected function validateContentCategory($category)
    {
        if (empty($category)) return null;
        
        // Ensure it's a string and not too long
        $category = substr((string)$category, 0, 100);
        
        // Remove any invalid characters
        return preg_replace('/[^\w\s>]/', '', $category);
    }
}