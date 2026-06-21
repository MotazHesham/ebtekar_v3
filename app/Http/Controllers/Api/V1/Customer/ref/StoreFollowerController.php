<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\StoreFollowRequest;
use App\Http\Resources\V1\Customer\StoreResource;
use App\Http\ResponseHelper;
use App\Models\Store;
use App\Models\StoreFollower;
use Illuminate\Support\Facades\Cache;

class StoreFollowerController extends Controller
{
    public function list()
    {
        $follows = Cache::remember('user-' . auth()->user()->id . '-follows-stores', config('panel.cache_time_long'), function () {
            return StoreFollower::where('user_id', auth()->user()->id)->get()->pluck('store_id')->toArray();
        });
        $stores = Store::whereIn('id', $follows)->orderBy('rating', 'desc')->paginate(10);
        return ResponseHelper::returnResource(StoreResource::collection($stores));
    }
    public function toggle(StoreFollowRequest $request)
    {
        $store = Store::findOrFail($request->store_id);
        $user = auth()->user();
        
        if($store->followers()->where('user_id', $user->id)->exists()) {
            $store->followers()->detach($user->id);
            Cache::forget('user-'.$user->id.'-follows-stores');
            return ResponseHelper::returnResponse(trans('api.success.unfollowed'));
        } else {
            $store->followers()->attach($user->id);
            Cache::forget('user-'.$user->id.'-follows-stores');
            return ResponseHelper::returnResponse(trans('api.success.followed')); 
        }
    }
}
