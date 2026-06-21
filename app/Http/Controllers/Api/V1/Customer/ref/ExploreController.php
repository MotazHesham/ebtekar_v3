<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\ExploreItemResource;
use App\Http\ResponseHelper;
use App\Models\ExploreItem;
use Illuminate\Support\Facades\Cache;

class ExploreController extends Controller
{
    public function index()
    {
        $items = Cache::remember('customer_explore_items', 300, function () {
            return ExploreItem::with(['media', 'banners.media'])
                ->where('active', 1)
                ->orderByDesc('order_level')
                ->get();
        });

        return ResponseHelper::returnResource(ExploreItemResource::collection($items));
    }
}
