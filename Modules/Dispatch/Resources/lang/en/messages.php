<?php

return [
    'queue_hint'           => 'Ready to dispatch (received at warehouse): :count',
    'assigned'             => 'Order :num assigned to courier',
    'invalid_status'       => 'Order :num is not in received-at-warehouse status',
    'courier_inactive'     => 'Courier is not active',
    'partner_mismatch'     => 'Courier does not belong to this shipping partner',
    'courier_at_capacity'  => 'Courier is at maximum active load',
    'shipment_not_found'   => 'Shipment not found',
    'no_courier_available' => 'No courier available for auto-assign',
    'batch_done'           => 'Dispatch finished: :success succeeded, :failed failed',
];
