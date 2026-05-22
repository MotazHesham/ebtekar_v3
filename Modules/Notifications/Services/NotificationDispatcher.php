<?php

namespace Modules\Notifications\Services;

use App\Jobs\SendPushNotification;
use App\Models\User;
use Illuminate\Support\Collection;
use Modules\Notifications\Entities\NotificationDelivery;
use Modules\Notifications\Enums\DeliveryStatus;
use Modules\Notifications\Enums\NotificationChannel;
use Modules\Shipping\Entities\Shipment;

class NotificationDispatcher
{
    public function __construct(protected ShippingNotificationRecipients $recipients)
    {
    }

    /**
     * @param  Collection<int, User>|User[]|null  $users
     */
    public function notifyUsers(
        string $eventType,
        string $title,
        string $body,
        ?string $link = null,
        ?Shipment $shipment = null,
        Collection|array|null $users = null,
        array $meta = [],
    ): void {
        $users = $users
            ? ($users instanceof Collection ? $users : collect($users))
            : ($shipment ? $this->recipients->forShipment($shipment) : $this->recipients->operationsStaff());

        if ($users->isEmpty()) {
            $this->logDelivery(
                NotificationChannel::Database,
                $eventType,
                $title,
                $body,
                DeliveryStatus::Skipped,
                null,
                $shipment?->id,
                array_merge($meta, ['reason' => 'no_recipients'])
            );

            return;
        }

        $siteSettings = get_site_setting();
        $link         = $link ?: ($shipment
            ? route('admin.delivery-orders.show', $shipment)
            : route('admin.delivery-orders.index'));

        foreach ($users as $user) {
            $this->logDelivery(
                NotificationChannel::Database,
                $eventType,
                $title,
                $body,
                DeliveryStatus::Sent,
                $user->id,
                $shipment?->id,
                array_merge($meta, ['link' => $link])
            );
        }

        $tokens = $this->recipients->deviceTokensFor($users);
        if ($tokens && config('notifications.push_enabled', true)) {
            try {
                SendPushNotification::dispatch($title, $body, $tokens, $link, $siteSettings);

                foreach ($users as $user) {
                    if (! $user->device_token) {
                        continue;
                    }
                    $this->logDelivery(
                        NotificationChannel::Push,
                        $eventType,
                        $title,
                        $body,
                        DeliveryStatus::Sent,
                        $user->id,
                        $shipment?->id,
                        array_merge($meta, ['link' => $link])
                    );
                }
            } catch (\Throwable $e) {
                foreach ($users as $user) {
                    $this->logDelivery(
                        NotificationChannel::Push,
                        $eventType,
                        $title,
                        $body,
                        DeliveryStatus::Failed,
                        $user->id,
                        $shipment?->id,
                        array_merge($meta, ['error' => $e->getMessage()])
                    );
                }
            }
        }
    }

    public function notifyOpsAlert(string $eventType, string $title, string $body, array $meta = []): void
    {
        $this->notifyUsers($eventType, $title, $body, null, null, $this->recipients->operationsStaff(), $meta);
    }

    protected function logDelivery(
        NotificationChannel $channel,
        string $eventType,
        string $title,
        string $body,
        DeliveryStatus $status,
        ?int $userId,
        ?int $shipmentId,
        array $meta = [],
    ): void {
        NotificationDelivery::create([
            'channel'           => $channel->value,
            'event_type'        => $eventType,
            'delivery_order_id' => $shipmentId,
            'user_id'           => $userId,
            'title'             => $title,
            'body'              => $body,
            'status'            => $status->value,
            'meta'              => $meta ?: null,
            'sent_at'           => $status === DeliveryStatus::Sent ? now() : null,
            'created_at'        => now(),
        ]);
    }
}
