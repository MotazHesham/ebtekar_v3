<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\NotificationResource;
use App\Http\ResponseHelper;
use App\Models\UserAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function list()
    {
        $notifications = UserAlert::whereHas('users', function ($query) {
            $query->where('users.id', auth()->user()->id);
        })->orderBy('created_at', 'desc')->cursorPaginate(10);


        DB::table('user_user_alert')
            ->where('user_id', auth()->user()->id)
            ->where('read', false)
            ->update(['read' => true]);

        return ResponseHelper::returnResource(NotificationResource::collection($notifications));
    }
}
