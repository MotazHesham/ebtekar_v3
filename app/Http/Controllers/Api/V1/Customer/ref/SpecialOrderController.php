<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Exceptions\UserFriendlyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\SpecialOrderPaymentRequest;
use App\Http\Requests\Api\V1\Customer\SpecialOrderRateRequest;
use App\Http\Requests\Api\V1\Customer\SpecialOrderRequest;
use App\Http\Resources\V1\Customer\OrderListResource;
use App\Http\Resources\V1\Customer\OrderSingleResource;
use App\Http\Resources\V1\Customer\SpecialOrderSingleResource;
use App\Http\ResponseHelper;
use App\Models\Order;
use App\Models\SpecialOrder;
use App\Services\SpecialOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecialOrderController extends Controller
{
    public function __construct(
        protected SpecialOrderService $specialOrderService,
    ) {}
    public function list()
    {
        $orders = Order::where('user_id', auth()->user()->id)
            ->special()
            ->with('store');

        if (getRequestHelper('status')) {
            switch (getRequestHelper('status')) {
                case 'pending':
                    $orders->pending();
                    break;
                case 'waiting_payment':
                    $orders->waitingPayment();
                    break;
                case 'current':
                    $orders->current();
                    break;
                case 'finished':
                    $orders->finished();
                    break;
            }
        }
        $orders = $orders->orderBy('created_at', 'desc')
            ->paginate(10);
        return ResponseHelper::returnResource(OrderListResource::collection($orders));
    }

    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->special()
            ->with('specialOrder')
            ->firstOrFail();
        return ResponseHelper::returnResource(new OrderSingleResource($order));
    }
    public function request(SpecialOrderRequest $request)
    {
        $user = $request->user();
        DB::transaction(function () use ($request, $user) {
            $this->specialOrderService->createOrder($request, $user);
        });
        return ResponseHelper::returnResponse(trans('api.success.orderCreated'));
    }

    public function acceptOffer(Request $request)
    {
        $order = Order::where('id', $request->id)
            ->where('user_id', auth()->user()->id)
            ->special()
            ->firstOrFail();

        $order->update(['order_status' => 'accepted_offer']);
        // TODO: Send notification to store

        return ResponseHelper::returnResponse(trans('api.success.success'));
    }

    public function refuseOffer(Request $request)
    {
        $order = Order::where('id', $request->id)
            ->where('user_id', auth()->user()->id)
            ->special()
            ->firstOrFail();
        if ($order->payment_status == 'paid') {
            throw new UserFriendlyException(trans('api.errors.cannotCancel'));
        }
        $order->update(['order_status' => 'rejected_offer']);
        // TODO: Send notification to store
        return ResponseHelper::returnResponse(trans('api.success.success'));
    }

    public function pay(SpecialOrderPaymentRequest $request)
    {
        $order = Order::where('id', $request->id)
            ->where('user_id', auth()->user()->id)
            ->special()
            ->firstOrFail();
        return $this->specialOrderService->pay($order, $request->payment_method_id);
    }
    public function rate(SpecialOrderRateRequest $request)
    {
        $order = Order::where('id', $request->id)
            ->where('user_id', auth()->user()->id)
            ->special()
            ->firstOrFail();
        if ($order->delivery_status != 'delivered_from_store') {
            throw new UserFriendlyException(trans('api.errors.cannotRate'));
        }
        $order->rate = $request->rate;
        $order->delivery_status = 'client_received';
        $order->save();
        return ResponseHelper::returnResponse(trans('api.success.rated'));
    }
}
