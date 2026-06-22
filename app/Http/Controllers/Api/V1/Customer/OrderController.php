<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\OrderListResource;
use App\Http\Resources\V1\Customer\OrderSingleResource;
use App\Http\ResponseHelper;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use App\Utils\OrderUtility;

class OrderController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
    ) {}

    public function list()
    {
        $orders = Order::withCount('orderDetails')->with('orderDetails.product')->where('user_id', auth()->user()->id);

        if (getRequestHelper('status')) {
            switch (getRequestHelper('status')) {
                case 'pending':
                    $orders->whereIn('delivery_status', ['pending', 'on_review', 'delay']);
                    break;
                case 'on_delivery':
                    $orders->where('delivery_status', 'on_delivery');
                    break;
                case 'delivered':
                    $orders->where('delivery_status', 'delivered');
                    break;
                case 'cancel':
                    $orders->where('delivery_status', 'cancel');
                    break;
            }
        }
        $orders = $orders->orderBy('created_at', 'desc')->cursorPaginate(10);
        return ResponseHelper::returnResource(OrderListResource::collection($orders));
    }
    public function show($id)
    {
        $order = Order::with('orderDetails.product')->withCount('orderDetails')->where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->firstOrFail();
        return ResponseHelper::returnResource(new OrderSingleResource($order));
    }

    public function track($id)
    {
        $order = Order::with('deliveryOrder')->where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        return ResponseHelper::returnResponse('', OrderUtility::buildTrackingTimeline($order));
    }
}
