<?php

namespace Modules\Dispatch\Enums;

enum BatchType: string
{
    case ManualBulk = 'manual_bulk';
    case Auto       = 'auto';
}
