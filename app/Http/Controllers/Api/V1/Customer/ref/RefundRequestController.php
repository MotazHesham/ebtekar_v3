<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\StoreRefundRequest;
use App\Http\Requests\Api\V1\Customer\UpdateRefundDeliveryStatusRequest;
use App\Http\Resources\V1\Customer\RefundRequestListResource;
use App\Http\Resources\V1\Customer\RefundRequestSingleResource;
use App\Http\ResponseHelper;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\RefundRequest;
use App\Models\RefundRequestDetail;
use App\Utils\CouponUtility;
use App\Utils\MediaHandler;
use Illuminate\Support\Facades\DB;

class RefundRequestController extends Controller
{
    public function __construct(
        protected MediaHandler $mediaHandler
    ) {}
    public function list()
    {
        $refundRequests = RefundRequest::where('user_id', auth()->user()->id)
            ->with('store');

        if (getRequestHelper('status')) {
            switch (getRequestHelper('status')) {
                case 'pending':
                    $refundRequests->pending();
                    break;
                case 'current':
                    $refundRequests->current();
                    break;
                case 'finished':
                    $refundRequests->finished();
                    break;
            }
        }

        $refundRequests = $refundRequests->orderBy('created_at', 'desc')->paginate(10);
        return ResponseHelper::returnResource(RefundRequestListResource::collection($refundRequests));
    }
    public function show($id)
    {
        $refundRequest = RefundRequest::where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->with('order', 'refundRequestDetails.orderDetail.product', 'store')
            ->firstOrFail();

        return ResponseHelper::returnResource(new RefundRequestSingleResource($refundRequest));
    }
    public function refund(StoreRefundRequest $request)
    {
        $order = Order::where('id', $request->order_id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        if ($order->delivery_status != 'delivered_from_store' && $order->delivery_status != 'client_received') {
            return ResponseHelper::returnNotProcessed(trans('api.errors.cannotRefund'));
        }

        if (RefundRequest::where('order_id', $order->id)->exists()) {
            return ResponseHelper::returnNotProcessed(trans('api.errors.alreadyRefundRequested'));
        }
        DB::transaction(function () use ($order, $request) {
            $refundRequest = RefundRequest::create([
                'user_id' => auth()->user()->id,
                'order_id' => $order->id,
                'store_id' => $order->store_id,
                'refund_amount' => 0,
                'refund_status' => 'pending',
                'reason' => $request->reason,
            ]);

            $totalRefundAmount = 0;
            if ($order->order_type == 'stander') {
                foreach ($request->order_details as $raw) {
                    $orderDetail = OrderDetail::findOrFail($raw['id']);
                    if ($orderDetail->order_id != $order->id || $orderDetail->quantity < $raw['quantity']) {
                        throw new \Exception(trans('api.errors.notValidValue'));
                    }
                    if($orderDetail->product &&!$orderDetail->product->refundable){
                        throw new \Exception(trans('api.errors.productNotRefundable',['product_name' => $orderDetail->product->name]));
                    }
                    $price = $orderDetail->price / $raw['quantity'];
                    $totalRefundAmount += $price * $raw['quantity'];
                    RefundRequestDetail::create([
                        'refund_request_id' => $refundRequest->id,
                        'order_detail_id' => $raw['id'],
                        'quantity' => $raw['quantity'],
                        'product_id' => $orderDetail->product_id,
                    ]);
                } 
                if($order->coupon_discount > 0){
                    // Calculate the discount percentage of the order
                    $discountPercentage = ($order->coupon_discount * 100) / $order->sub_total;
                    // Calculate the discount amount of the order
                    $discountAmount = $totalRefundAmount * ($discountPercentage / 100); 
                    // Subtract the discount amount from the total refund amount
                    $totalRefundAmount -= $discountAmount;
                }
                // Calculate the vat of the total refund amount
                $vat = calculateVat($totalRefundAmount);
                $totalRefundAmount += $vat;
            }else{
                $totalRefundAmount = $order->total - $order->shipping_cost;
            }
            $refundRequest->refund_amount = $totalRefundAmount;
            $refundRequest->save();

            if ($request->has('invoice_photo')) {
                $this->mediaHandler->handleMediaUpload($refundRequest, 'invoice_photo', $request->invoice_photo, ['api' => true]);
            }
            if ($request->has('product_photo')) {
                $this->mediaHandler->handleMediaUpload($refundRequest, 'product_photo', $request->product_photo, ['api' => true]);
            }
        });
        return ResponseHelper::returnResponse(trans('api.success.success'));
    }
    public function updateDeliveryStatus(UpdateRefundDeliveryStatusRequest $request)
    {
        $refundRequest = RefundRequest::where('user_id', auth()->user()->id)
            ->where('id', $request->id)
            ->firstOrFail();
        if($refundRequest->refund_status != 'approved'){
            return ResponseHelper::returnNotProcessed(trans('api.errors.cannotUpdateDeliveryStatus'));
        }
        $refundRequest->delivery_status = $request->delivery_status;
        $refundRequest->save();
        // TODO: Send notification to store
        return ResponseHelper::returnResponse(trans('api.success.success'));
    }
}
