<?php

namespace Modules\Settlement\Enums;

enum SettlementStatus: string
{
    case Pending   = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
}
