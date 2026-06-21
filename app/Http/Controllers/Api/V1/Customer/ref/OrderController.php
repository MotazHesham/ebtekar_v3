<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Exceptions\UserFriendlyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\CheckoutRequest;
use App\Http\Requests\Api\V1\Customer\OrderPaymentRequest;
use App\Http\Requests\Api\V1\Customer\OrderRateRequest;
use App\Http\Resources\V1\Customer\CartResource;
use App\Http\Resources\V1\Customer\OrderListResource;
use App\Http\Resources\V1\Customer\OrderSingleResource;
use App\Http\ResponseHelper;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use App\Utils\NotificationUtility;
use App\Utils\AddressUtility;
use App\Utils\CouponUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
        protected AddressUtility $addressUtility,
        protected CouponUtility $couponUtility,
    ) {}

    public function checkout(CheckoutRequest $request)
    {
        DB::transaction(function () {
            $cart = $this->cartService->getCart();
            $cartItems = $cart->cartItems()->with('productStock', 'product')->get();

            throw_if($cartItems->isEmpty(), UserFriendlyException::class, trans('api.errors.cartIsEmpty'));

            $this->orderService->checkStockAvailability($cartItems);

            $couponData = optional($cart->coupon_code, fn($code) => $this->couponUtility->applyCoupon($code, $cart->store_id, $cart->user_id));

            $this->orderService->createOrder($cart, $cartItems, $couponData);
            $this->cartService->emptyCart($cart);
        });
        return ResponseHelper::returnResponse(trans('api.success.orderCreated'));
    }
    public function list()
    {
        $orders = Order::where('user_id', auth()->user()->id)
            ->standerd()
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
        $orders = $orders->orderBy('created_at', 'desc')->paginate(10);
        return ResponseHelper::returnResource(OrderListResource::collection($orders));
    }
    public function show($id)
    {
        $order = Order::where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->with('store', 'orderOrderDetails.product')
            ->firstOrFail();
        return ResponseHelper::returnResource(new OrderSingleResource($order));
    }
    public function pay(OrderPaymentRequest $request)
    {
        $order = Order::where('id', $request->id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();
        return $this->orderService->pay($order, $request->payment_method_id);
    }
    public function cancel(Request $request)
    {
        $order = Order::where('id', $request->id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();
        $this->orderService->cancel($order);
        return ResponseHelper::returnResponse(trans('api.success.canceled'));
    }
    public function rate(OrderRateRequest $request)
    {
        $order = Order::where('id', $request->id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();
        if ($order->delivery_status != 'delivered_from_store') {
            throw new UserFriendlyException(trans('api.errors.cannotRate'));
        }
        $order->rate = $request->rate;
        $order->delivery_status = 'client_received';
        $order->save();
        NotificationUtility::sendOrderStatusNotification($order, 'client_received');
        return ResponseHelper::returnResponse(trans('api.success.rated'));
    }

    public function reorder($id)
    {
        $order = Order::where('user_id', auth()->user()->id)
            ->standerd()
            ->with('orderOrderDetails.product')
            ->where('id', $id)
            ->firstOrFail();

        $cart = $this->cartService->getCart();

        $firstProduct = $order->orderOrderDetails->first()?->product;
        if ($firstProduct && $this->cartService->checkItemsFromSameStore($cart, $firstProduct)) {
            return ResponseHelper::returnNotProcessed(
                trans('api.errors.productsFromDifferentStores')
            );
        }

        $cart = $this->orderService->reorderToCart($order, $cart);

        return ResponseHelper::returnResource(new CartResource($cart), trans('api.success.reordered'));
    }
}
