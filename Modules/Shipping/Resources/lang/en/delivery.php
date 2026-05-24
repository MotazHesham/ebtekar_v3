<?php

return [
    'timeline' => [
        'created'                 => 'Delivery record created',
        'status_changed'          => 'Status changed: :from → :to',
        'assigned_to'             => 'Assigned to: :name',
        'admin_managed_auto_received' => 'Received at warehouse (admin-managed partner — receive scan skipped)',
        'marked_delivered'        => 'Marked as delivered',
        'revert_handoff'          => 'Reverted from handed to partner',
        'cancel_delivered'        => 'Delivery status cancelled',
        'cancel_return'           => 'Return status cancelled',
    ],
    'dashboard' => [
        'today_received'      => 'Received Today',
        'today_delivered'     => 'Delivered Today',
        'today_returns'       => 'Returns Today',
        'on_delivery'         => 'Out For Delivery',
        'total_shipping'      => 'Total Shipping Cost',
        'total_cod'           => 'Total COD',
        'total_cod_collect'   => 'Total to Collect',
        'total_cod_collected' => 'Total Collected',
        'total_returns_amount'=> 'Total Returns Amount',
    ],
    'fields' => [
        'full_address' => 'Full address',
        'row_num'      => '#',
    ],
    'actions' => [
        'mark_delivered'  => 'Delivered',
        'revert_handoff'  => 'Revert handoff',
        'export_selected' => 'Export selected',
        'export_all'      => 'Export all',
    ],
    'list' => [
        'selected_cod_total' => 'Selected remaining COD total',
    ],
    'messages' => [
        'status_updated'   => 'Status updated successfully',
        'confirm_status'   => 'Are you sure?',
        'confirm_ok'       => 'OK',
        'confirm_cancel'   => 'Cancel',
    ],
    'scan' => [
        'menu'        => 'Scan order',
        'title'       => 'Courier order scan',
        'enter_code'  => 'Enter order number or barcode',
        'or_manual'   => 'Or enter code manually',
        'lookup'      => 'Lookup',
        'order_found' => 'Order details',
    ],
    'admin_actions' => [
        'title'            => 'Admin actions',
        'cancel_delivered' => 'Cancel delivered',
        'cancel_return'    => 'Cancel return',
    ],
    'errors' => [
        'cannot_mark_delivered'    => 'Cannot mark delivered in current status',
        'cannot_revert_handoff'    => 'Can only revert from handed to partner status',
        'not_delivered'            => 'Shipment is not delivered',
        'not_return'               => 'Shipment is not a return',
        'courier_partner_mismatch' => 'Courier does not belong to this shipping partner',
    ],
];
