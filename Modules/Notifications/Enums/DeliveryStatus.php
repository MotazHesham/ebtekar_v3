<?php

namespace Modules\Notifications\Enums;

enum DeliveryStatus: string
{
    case Pending = 'pending';
    case Sent    = 'sent';
    case Failed  = 'failed';
    case Skipped = 'skipped';
}
