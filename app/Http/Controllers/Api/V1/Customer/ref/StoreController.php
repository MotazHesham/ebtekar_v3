<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\StoreComplaintRequest;
use App\Http\Requests\Api\V1\Customer\StoreFollowRequest;
use App\Http\Resources\V1\Customer\StoreResource;
use App\Http\ResponseHelper;
use App\Models\Store;
use App\Models\StoreComplaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StoreController extends Controller
{
    public function bestStores()
    {
        $stores = Store::verified()
            ->with('media')
            ->withSum('products', 'num_of_sale')
            ->orderByDesc('products_sum_num_of_sale')
            ->take(6)
            ->get();

        return ResponseHelper::returnResource(StoreResource::collection($stores));
    }

    public function storeDetail($storeId)
    {
        $store = Store::findOrFail($storeId);
        $store->load('media','city');
        return ResponseHelper::returnResource(StoreResource::make($store));
    }

    public function complaint(StoreComplaintRequest $request)
    { 
        StoreComplaint::create([
            'store_id' => $request->store_id,
            'user_id' => auth()->user()->id,
            'reason' => $request->complaint,
        ]);
        return ResponseHelper::returnResponse(trans('api.success.complaintSent'));
    }

}
