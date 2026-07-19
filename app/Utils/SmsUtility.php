<?php

namespace App\Utils;

use App\Models\SmsTemplate;
use App\Jobs\SendSmsJob;

class SmsUtility
{
    public static function phone_number_verification($phone, $code)
    {
        $sms_body = 'مرحباً بك في ' . env('APP_NAME') . "\n";
        $sms_body .= 'رمز التحقق الخاص بك هو: ' . $code . "\n";
        $sms_body .= 'هذا الرمز صالح لمدة 5 دقائق فقط.';
        SendSmsJob::dispatch($phone, env('APP_NAME'), $sms_body);
    }

    public static function password_reset($phone, $code)
    {
        $sms_body = 'مرحباً بك في ' . env('APP_NAME') . "\n";
        $sms_body .= 'رمز إعادة تعيين كلمة المرور الخاص بك هو: ' . $code . "\n";
        $sms_body .= 'إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذه الرسالة.';
        SendSmsJob::dispatch($phone, env('APP_NAME'), $sms_body);
    }
}
