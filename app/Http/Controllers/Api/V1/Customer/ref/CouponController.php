<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\CouponResource;
use App\Http\ResponseHelper;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    
    public function coupons()
    {
        return ResponseHelper::returnResource(
            CouponResource::collection(Coupon::active()->orderBy('created_at','desc')->with('stores')->get()),
        );
    }
}
