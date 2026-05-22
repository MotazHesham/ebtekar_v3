<?php

namespace Modules\Tracking\Enums;

enum ScanType: string
{
    case Handoff = 'handoff';
    case Receive = 'receive';
}
