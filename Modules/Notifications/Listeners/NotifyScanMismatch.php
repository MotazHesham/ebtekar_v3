<?php

namespace Modules\Notifications\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notifications\Services\NotificationDispatcher;
use Modules\Notifications\Services\ShippingNotificationMessages;
use Modules\Tracking\Events\ScanMismatchDetected;

class NotifyScanMismatch implements ShouldQueue
{
    public function handle(ScanMismatchDetected $event): void
    {
        [$title, $body] = app(ShippingNotificationMessages::class)->scanMismatch(
            $event->barcode,
            $event->reason
        );

        app(NotificationDispatcher::class)->notifyOpsAlert(
            'tracking.scan_mismatch',
            $title,
            $body,
            [
                'barcode'              => $event->barcode,
                'expected_partner_id'  => $event->expectedPartnerId,
                'actual_partner_id'    => $event->actualPartnerId,
            ]
        );
    }
}
