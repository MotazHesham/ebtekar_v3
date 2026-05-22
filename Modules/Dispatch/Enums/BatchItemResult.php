<?php

namespace Modules\Dispatch\Enums;

enum BatchItemResult: string
{
    case Success = 'success';
    case Skipped = 'skipped';
    case Error   = 'error';
}
