<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\PopupResource;
use App\Http\ResponseHelper;
use App\Models\Popup;

class PopupController extends Controller
{
    public function popups()
    {
        $popup = Popup::where('active', 1)->inRandomOrder()->take(1)->first();

        return ResponseHelper::returnResource(new PopupResource($popup));
    }
}
