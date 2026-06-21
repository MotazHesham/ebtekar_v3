<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\NotificationResource;
use App\Http\ResponseHelper;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notificationList(10);
        return ResponseHelper::returnResource(NotificationResource::collection($notifications));
    }

    public function read(Request $request)
    {
        $notification = Notification::where('id', $request->id)->where('notifiable_id', auth()->user()->id)->first();
        if($notification){
            $notification->read_at = date(config('panel.date_format') . ' ' . config('panel.time_format'));
            $notification->save();
            return ResponseHelper::returnResponse(trans('api.success.success'));
        }
        return ResponseHelper::returnNotProcessed(trans('api.errors.not_found'));
    }
}
