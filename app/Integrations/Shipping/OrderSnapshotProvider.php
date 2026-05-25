<?php

namespace App\Integrations\Shipping;

use App\Contracts\Shipping\OrderReference;
use App\Contracts\Shipping\OrderSnapshot;
use App\Contracts\Shipping\OrderSnapshotProviderContract;
use App\Models\Order;
use App\Models\ReceiptCompany;
use App\Models\ReceiptSocial;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Sole integration point between core orders and shipping modules.
 */
class OrderSnapshotProvider implements OrderSnapshotProviderContract
{
    public function resolveByBarcode(string $barcode): ?OrderReference
    {
        $parts = explode('-', trim($barcode), 2);
        if (count($parts) < 2) {
            return null;
        }

        $prefix = strtoupper($parts[0]);
        if (! in_array($prefix, ['O', 'S', 'C'], true)) {
            return null;
        }

        $id = (int) $parts[1];
        if ($id < 1) {
            return null;
        }

        $model = match ($prefix) {
            'O'     => Order::withoutGlobalScope('completed')->find($id),
            'S'     => ReceiptSocial::find($id),
            'C'     => ReceiptCompany::find($id),
            default => null,
        };

        if (! $model) {
            return null;
        }

        return new OrderReference(get_class($model), $model->id, $prefix);
    }

    public function resolveByScanCode(string $code): ?OrderReference
    {
        $code = trim($code);
        if ($code === '') {
            return null;
        }

        return $this->resolveByBarcode($code) ?? $this->resolveByOrderNum($code);
    }

    protected function resolveByOrderNum(string $orderNum): ?OrderReference
    {
        $order = ReceiptCompany::where('order_num', $orderNum)->first();
        if ($order) {
            return new OrderReference(ReceiptCompany::class, $order->id, 'C');
        }

        $order = Order::withoutGlobalScope('completed')->where('order_num', $orderNum)->first();
        if ($order) {
            return new OrderReference(Order::class, $order->id, 'O');
        }

        $order = ReceiptSocial::where('order_num', $orderNum)->first();
        if ($order) {
            return new OrderReference(ReceiptSocial::class, $order->id, 'S');
        }

        return null;
    }

    public function snapshot(OrderReference $reference): ?OrderSnapshot
    {
        $model = $this->resolveModel($reference);
        if (! $model) {
            return null;
        }

        $governorate = null;
        if (method_exists($model, 'shipping_country') && $model->relationLoaded('shipping_country') === false) {
            $model->load('shipping_country');
        }
        if (isset($model->shipping_country)) {
            $governorate = $model->shipping_country?->name;
        }

        $cod = method_exists($model, 'calc_total_for_delivery') ? (float) $model->calc_total_for_delivery() : 0.0;

        $productsSummary = method_exists($model, 'get_products_details') ? $model->get_products_details() : null;

        return new OrderSnapshot(
            orderNum: (string) ($model->order_num ?? ''),
            clientName: $model->client_name ?? null,
            phoneNumber: $model->phone_number ?? null,
            governorate: $governorate,
            region: $model->shipping_address ?? null,
            codAmount: $cod,
            depositAmount: (float) ($model->deposit_amount ?? 0),
            remainingCod: max(0, $cod),
            shippingCost: (float) ($model->shipping_country_cost ?? 0),
            paymentStatus: $model->payment_status ?? null,
            clientNote: $model->note ?? null,
            productsSummary: $productsSummary,
        );
    }

    public function syncDeliveryStatus(OrderReference $reference, string $shipmentStatus): void
    {
        $model = $this->resolveModel($reference);
        if (! $model || ! array_key_exists('delivery_status', $model->getAttributes())) {
            return;
        }

        $map = [
            'pending'               => 'pending',
            'handed_to_partner'     => 'on_review',
            'received_at_warehouse' => 'on_review',
            'out_with_courier'      => 'on_delivery',
            'delivered'             => 'delivered',
            'returned'              => 'cancel',
            'refused'               => 'cancel',
            'postponed'             => 'delay',
            'customer_unavailable'  => 'delay',
            'retry'                 => 'on_delivery',
            'closed'                => 'cancel',
        ];

        if (! isset($map[$shipmentStatus])) {
            return;
        }

        $model->delivery_status = $map[$shipmentStatus];

        if ($shipmentStatus === 'delivered') {
            $model->done      = 1;
            $model->done_time = Carbon::now()->format(
                config('panel.date_format') . ' ' . config('panel.time_format')
            );
        }

        $model->save();
    }

    protected function resolveModel(OrderReference $reference): ?Model
    {
        return match ($reference->morphType()) {
            Order::class          => Order::withoutGlobalScope('completed')->find($reference->id),
            ReceiptSocial::class  => ReceiptSocial::find($reference->id),
            ReceiptCompany::class => ReceiptCompany::find($reference->id),
            default               => null,
        };
    }
}
