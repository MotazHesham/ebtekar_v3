<?php

namespace App\Utils;

use App\Models\Order;
use Carbon\Carbon;

class OrderUtility
{
    public static function handlePaymentSuccess(Order $order, $paymentStatus)
    {

        if ($order->payment_status == 'paid') {
            return;
        }

        $order->payment_status = $paymentStatus;
        $order->completed = 1;
        if ($paymentStatus == 'paid') {
            $order->deposit_amount = $order->calc_total() - $order->calc_discount();
        }
        $order->save();
    }

    public static function buildTrackingTimeline(Order $order): array
    {
        $shipment = $order->deliveryOrder;
        $current = $order->delivery_status;

        $definitions = [
            'pending' => [
                'title' => 'تم استلام الطلب',
                'description' => 'تم تأكيد طلبك بنجاح',
                'date' => fn() => $order->created_at,
            ],
            'on_review' => [
                'title' => 'تم المراجعة',
                'description' => 'جاري مراجعة طلبك',
                'date' => fn() => self::formatTrackingDate(
                    $shipment?->received_by_partner_at ?? $shipment?->handed_to_partner_at
                ) ?? $order->send_to_playlist_date,
            ],
            'on_delivery' => [
                'title' => 'في الطريق',
                'description' => 'طلبك في طريقه إليك',
                'date' => fn() => self::formatTrackingDate($shipment?->out_with_courier_at)
                    ?? $order->send_to_delivery_date,
            ],
            'delivered' => [
                'title' => 'تم التسليم',
                'description' => 'تم التسليم بنجاح',
                'date' => fn() => self::formatTrackingDate($shipment?->delivered_at)
                    ?? $order->done_time,
            ],
            'delay' => [
                'title' => 'تأخير في التوصيل',
                'description' => $order->delay_reason ?: 'حدث تأخير في توصيل طلبك',
                'date' => fn() => $order->updated_at,
            ],
            'cancel' => [
                'title' => 'تم الإلغاء',
                'description' => $order->cancel_reason ?: 'تم إلغاء الطلب',
                'date' => fn() => $order->updated_at,
            ],
        ];

        $flow = ['pending', 'on_review', 'on_delivery', 'delivered'];
        $effectiveStatus = $current === 'delay' ? 'on_delivery' : $current;
        $currentIndex = array_search($effectiveStatus, $flow, true);

        $steps = [];

        foreach ($flow as $index => $status) {
            $def = $definitions[$status];
            $date = $def['date']();

            if ($current === 'cancel') {
                $isCompleted = $status === 'pending' || $date !== null;
                $isCurrent = false;
            } else {
                $isCompleted = $currentIndex !== false && $index < $currentIndex;
                $isCurrent = $status === $effectiveStatus
                    && ! in_array($current, ['delivered', 'cancel'], true);
            }

            $isReached = $isCompleted || $isCurrent || ($status === 'delivered' && $current === 'delivered');

            $steps[] = [
                'status' => $status,
                'title' => $def['title'],
                'description' => $def['description'],
                'date' => $isReached ? $date : null,
                'is_completed' => $isCompleted || ($status === 'delivered' && $current === 'delivered'),
                'is_current' => $isCurrent || ($current === 'delay' && $status === 'on_delivery'),
            ];
        }

        if (in_array($current, ['delay', 'cancel'], true)) {
            $def = $definitions[$current];
            $steps[] = [
                'status' => $current,
                'title' => $def['title'],
                'description' => $def['description'],
                'date' => $def['date'](),
                'is_completed' => false,
                'is_current' => true,
            ];
        }

        return [
            'order_id' => $order->id,
            'order_num' => $order->order_num,
            'current_status' => $current,
            'steps' => $steps,
        ];
    }

    protected static function formatTrackingDate($value): ?string
    {
        if (! $value) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        }

        return (string) $value;
    }
}
