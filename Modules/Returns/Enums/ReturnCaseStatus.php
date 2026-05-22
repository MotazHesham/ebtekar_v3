<?php

namespace Modules\Returns\Enums;

enum ReturnCaseStatus: string
{
    case Open               = 'open';
    case WarehouseReceived  = 'warehouse_received';
    case Closed             = 'closed';
    case Cancelled          = 'cancelled';
}
