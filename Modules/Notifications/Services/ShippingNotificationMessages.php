<?php

namespace Modules\Notifications\Services;

use Modules\Shipping\Entities\Shipment;

class ShippingNotificationMessages
{
    public function statusChanged(Shipment $shipment, string $newStatus, ?string $note = null): array
    {
        $statusLabel = trans()->has('delivery_order_status.' . $newStatus)
            ? __('delivery_order_status.' . $newStatus)
            : $newStatus;

        $title = __('notifications::messages.status_title', ['num' => $shipment->order_num]);
        $body  = __('notifications::messages.status_body', [
            'num'    => $shipment->order_num,
            'status' => $statusLabel,
        ]);

        if ($note) {
            $body .= ' — ' . $note;
        }

        return [$title, $body];
    }

    public function created(Shipment $shipment): array
    {
        return [
            __('notifications::messages.created_title', ['num' => $shipment->order_num]),
            __('notifications::messages.created_body', ['num' => $shipment->order_num]),
        ];
    }

    public function courierAssigned(Shipment $shipment, string $courierLabel): array
    {
        return [
            __('notifications::messages.assign_title', ['num' => $shipment->order_num]),
            __('notifications::messages.assign_body', ['num' => $shipment->order_num, 'courier' => $courierLabel]),
        ];
    }

    public function returned(Shipment $shipment, string $reasonLabel): array
    {
        return [
            __('notifications::messages.return_title', ['num' => $shipment->order_num]),
            __('notifications::messages.return_body', ['num' => $shipment->order_num, 'reason' => $reasonLabel]),
        ];
    }

    public function settlementClosed(string $courierName, float $collected, float $difference): array
    {
        return [
            __('notifications::messages.settlement_title'),
            __('notifications::messages.settlement_body', [
                'courier'    => $courierName,
                'collected'  => number_format($collected, 2),
                'difference' => number_format($difference, 2),
            ]),
        ];
    }

    public function scanMismatch(string $barcode, string $reason): array
    {
        return [
            __('notifications::messages.mismatch_title'),
            __('notifications::messages.mismatch_body', ['code' => $barcode, 'reason' => $reason]),
        ];
    }
}
