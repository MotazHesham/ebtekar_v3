<?php

namespace Modules\Courier\Enums;

enum CourierStatus: string
{
    case Active   = 'active';
    case Inactive = 'inactive';
}
