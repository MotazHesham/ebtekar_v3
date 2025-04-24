<?php

namespace App\Services;

use App\Models\User;
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

    public function sendEventFromController($contentData, $extraUserData = null)
    { 
        $event = $this->createEvent($contentData['event'], $extraUserData, $contentData);
        $this->sendEvent($event,$contentData['event']);
    } 

    public function sendEventPageView()
    { 
        // Create UserData with enhanced matching parameters
        $userDataObj = $this->getUserData();  

        // Create Event
        $event =  (new Event())
                    ->setEventName('PageView')
                    ->setEventTime(time())
                    ->setEventSourceUrl(request()->fullUrl())
                    ->setUserData($userDataObj) 
                    ->setActionSource(ActionSource::WEBSITE);
        $this->sendEvent($event,'PageView');
    }

    public function sendEventSearch($search)
    { 
        // Create UserData with enhanced matching parameters
        $userDataObj = $this->getUserData(); 
        
        // Create CustomData
        $customData = (new CustomData())->setSearchString($search);
        // Create Event
        $event =  (new Event())
                    ->setEventName('Search')
                    ->setEventTime(time())
                    ->setEventSourceUrl(request()->fullUrl())
                    ->setUserData($userDataObj)
                    ->setCustomData($customData)
                    ->setActionSource(ActionSource::WEBSITE);
        $this->sendEvent($event,'Search');
    }

    protected function createEvent($eventName, $extraUserData, $contentData)
    {  
        // Create UserData with enhanced matching parameters
        $userDataObj = $this->getUserData(); 

        // Validate content_ids is an array of strings
        if (isset($contentData['content_ids'])) {
            $contentData['content_ids'] = array_map('strval', (array)$contentData['content_ids']);
        }
        // Create CustomData
        $customData = (new CustomData())
            ->setContentName($contentData['content_name'] ?? null)
            ->setContentIds($contentData['content_ids'] ?? null)
            ->setContentType($contentData['content_type'] ?? null) 
            ->setContentCategory($contentData['content_category'] ?? null)
            ->setValue($this->validateValue($contentData['value'] ?? null))
            ->setCurrency($this->validateCurrency($contentData['currency'] ?? 'EGP'))
            ->setNumItems($contentData['num_items'] ?? null);

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
            $request->execute(); 
        } catch (\Exception $e) {
            Log::error('Facebook CAPI Failed: eventName => ' . $eventName, ['error' => $e]);
        }
    }

    protected function getUserData()
    {
        $userData = [
            'fbp' => request()->cookie('_fbp'),
            'fbc' => request()->cookie('_fbc'),
        ];

        if(auth()->check()){ 
            $user = User::find(auth()->id());
            $userData['external_id'] =  $user->id;
            $userData['email'] =  $user->hashedEmail();
            $userData['phone'] =  $user->hashedPhone();
            $userData['firstName'] =  $user->hashedFirstName();
            $userData['lastName'] =  $user->hashedLastName();
        }

        $userDataObj =(new UserData())
            ->setClientIpAddress(request()->ip())
            ->setClientUserAgent(request()->userAgent())
            ->setFbp($this->getFbpFromCookie())
            ->setFbc($this->getFbcFromCookie())
            ->setExternalId($userData['external_id'] ?? null)
            ->setEmail($userData['email'] ?? null)
            ->setPhone($userData['phone'] ?? null)
            ->setFirstName($userData['firstName'] ?? null)
            ->setLastName($userData['lastName'] ?? null)
            ->setCountryCode($extraUserData['country'] ?? null)
            ->setCity($extraUserData['city'] ?? null)
            ->setState($extraUserData['state'] ?? null);
        return $userDataObj;
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

    protected function validateValue($value)
    {
        return is_numeric($value) ? (float)$value : null;
    }

    protected function validateCurrency($currency)
    {
        return strlen($currency) === 3 ? $currency : 'USD';
    } 
}