<?php

namespace Modules\Notifications\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Placeholder for push / SMS / WhatsApp channels.
 */
class SendShipmentStatusNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $shipmentId,
        public string $status,
        public ?int $actorUserId = null,
    ) {
    }

    public function handle(): void
    {
        // Channel integrations (FCM, WhatsApp API) wired in Phase 2+
    }
}
