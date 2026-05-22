<?php

namespace Modules\Notifications\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notifications\Services\NotificationDispatcher;
use Modules\Notifications\Services\ShippingNotificationMessages;
use Modules\Shipping\Entities\Shipment;

class SendShipmentStatusNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $shipmentId,
        public string $status,
        public ?int $actorUserId = null,
        public ?string $note = null,
    ) {
    }

    public function handle(
        NotificationDispatcher $dispatcher,
        ShippingNotificationMessages $messages,
    ): void {
        $shipment = Shipment::find($this->shipmentId);
        if (! $shipment) {
            return;
        }

        [$title, $body] = $messages->statusChanged($shipment, $this->status, $this->note);

        $dispatcher->notifyUsers(
            'shipment.status_changed',
            $title,
            $body,
            route('admin.delivery-orders.show', $shipment),
            $shipment,
            meta: ['status' => $this->status, 'actor_user_id' => $this->actorUserId]
        );
    }
}
