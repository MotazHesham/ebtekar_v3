<?php

namespace Modules\Notifications\Enums;

enum NotificationChannel: string
{
    case Push     = 'push';
    case Database = 'database';
    case Sms      = 'sms';
    case Whatsapp = 'whatsapp';
}
