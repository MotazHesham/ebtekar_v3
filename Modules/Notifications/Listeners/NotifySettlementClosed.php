<?php

namespace Modules\Notifications\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notifications\Services\NotificationDispatcher;
use Modules\Notifications\Services\ShippingNotificationMessages;
use Modules\Settlement\Events\CourierSettlementClosed;

class NotifySettlementClosed implements ShouldQueue
{
    public function handle(CourierSettlementClosed $event): void
    {
        $settlement = $event->settlement->loadMissing('courier.user');
        $courierName = $settlement->courier?->user?->name ?? '#'.$settlement->deliver_man_id;

        [$title, $body] = app(ShippingNotificationMessages::class)->settlementClosed(
            $courierName,
            (float) $settlement->collected_amount,
            (float) $settlement->difference_amount
        );

        $users = collect();
        if ($settlement->courier?->user) {
            $users->push($settlement->courier->user);
        }
        $users = $users->merge(app(\Modules\Notifications\Services\ShippingNotificationRecipients::class)->operationsStaff())->unique('id');

        app(NotificationDispatcher::class)->notifyUsers(
            'settlement.closed',
            $title,
            $body,
            route('admin.settlements.show', $settlement),
            null,
            $users,
            ['settlement_id' => $settlement->id]
        );
    }
}
