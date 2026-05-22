<?php

namespace Modules\Tracking\Enums;

enum ScanResult: string
{
    case Success   = 'success';
    case Error     = 'error';
    case Mismatch  = 'mismatch';
    case Missing   = 'missing';
    case Duplicate = 'duplicate';
}
