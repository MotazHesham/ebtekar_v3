<?php

namespace App\Services;

use App\Models\User;
use App\Models\WebsiteSetting;
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

    public function __construct(WebsiteSetting $siteSetting)
    {
        $this->pixelId = $siteSetting->fb_pixel_id;
        $this->accessToken = $siteSetting->fb_access_token;
        if (empty($this->pixelId) || empty($this->accessToken)) {
            throw new \RuntimeException('Facebook Pixel ID or Access Token not configured');
        }
        $this->testEventCode = $siteSetting->fb_test_code;

        // Initialize the Facebook SDK
        Api::init(null, null, $this->accessToken);
    }

    public function sendEventFromServer($contentData, $userData)
    { 
        $event = $this->createEvent($contentData['event'], $userData, $contentData);
        $this->sendEvent($event,$contentData['event']);
    } 

    public function sendEventPageView($userData)
    { 
        // Create UserData with enhanced matching parameters
        $userDataObj = $this->prepareUserData( $userData);  

        // Create Event
        $event =  (new Event())
                    ->setEventName('PageView')
                    ->setEventTime(time())
                    ->setEventSourceUrl(request()->fullUrl())
                    ->setUserData($userDataObj) 
                    ->setActionSource(ActionSource::WEBSITE);
        $this->sendEvent($event,'PageView');
    }

    public function sendEventSearch($contentData, $userData)
    { 
        // Create UserData with enhanced matching parameters
        $userDataObj = $this->prepareUserData( $userData); 
        
        // Create CustomData
        $customData = (new CustomData())->setSearchString($contentData['search_string'] ?? null);
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

    protected function createEvent($eventName, $userData, $contentData)
    {  
        // Create UserData with enhanced matching parameters
        $userDataObj = $this->prepareUserData($userData); 

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

    protected function prepareUserData($userData)
    { 
        $userDataObj =(new UserData())
            ->setClientIpAddress($userData['ip'] ?? null)
            ->setClientUserAgent($userData['userAgent'] ?? null)
            ->setFbp($userData['fbp'] ?? null)
            ->setFbc($userData['fbc'] ?? null)
            ->setExternalId($userData['external_id'] ?? null)
            ->setEmail($userData['email'] ?? null)
            ->setPhone($userData['phone'] ?? null)
            ->setFirstName($userData['firstName'] ?? null)
            ->setLastName($userData['lastName'] ?? null);
        return $userDataObj;
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