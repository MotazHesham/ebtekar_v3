<?php

namespace App\Http\Controllers\Api\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Settings\CategoryResource;
use App\Http\Resources\V1\Settings\CountryResource;
use App\Http\Resources\V1\Settings\PaymentMethodResource;
use App\Http\ResponseHelper;
use App\Models\Category;
use App\Models\Contactu;
use App\Models\PaymentMethod;
use App\Models\Country;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return ResponseHelper::returnResponse('Settings fetched successfully', [
            'contact_email' => getSetting('contact_email'),
            'contact_phone' => getSetting('contact_phone'),
            'about_us' => getSetting('about_us'),
            'terms_and_conditions' => getSetting('terms_and_conditions'),
            'privacy_policy' => getSetting('privacy_policy'),
            'refund_policy' => getSetting('refund_policy'),
            'payment_policy' => getSetting('payment_policy'),
            'delivery_policy' => getSetting('delivery_policy'),
            'facebook' => getSetting('facebook'),
            'instagram' => getSetting('instagram'),
            'twitter' => getSetting('twitter'),
            'linkedin' => getSetting('linkedin'),
            'youtube' => getSetting('youtube'),
            'tiktok' => getSetting('tiktok'),
            'snapchat' => getSetting('snapchat'),
            'whatsapp' => getSetting('whatsapp'),
            'calendar_default_reminder_days' => (int) getSetting('calendar_default_reminder_days', 7),
        ]);
    }

    public function countries()
    {
        $countries = Country::where('website', 1)->where('status', 1)->where('type', 'countries')->get();
        return ResponseHelper::returnResource(CountryResource::collection($countries));
    }

    public function categories()
    {
        $categories = Category::all();
        return ResponseHelper::returnResource(CategoryResource::collection($categories));
    }

    public function paymentMethods()
    {
        $paymentMethods = PaymentMethod::where('active', 1)->get();
        return ResponseHelper::returnResource(PaymentMethodResource::collection($paymentMethods));
    }

    public function contactUs(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:' . config('panel.max_characters_short'),
            'phone' => 'required',
            'message' => 'required|string|max:' . config('panel.max_characters_long'),
        ]);
        $contactMassage = Contactu::create($request->merge([
            'created_from' => 'app',
        ])->all());
        return ResponseHelper::returnResponse(trans('api.success.contactSentSuccessfully'));
    }
}
