<?php

namespace App\Services;

use App\Models\OtpMethod;

class SendSmsService
{
    public function sendSMS($to, $from, $text)
    {
        $otp = OtpMethod::where('active', 1)->first();

        if ($otp) {
            $otp_class = __NAMESPACE__ . '\\OTP\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $otp->key)));
        } else {
            $otp_class = null;
        }

        if (class_exists($otp_class)) {
            return (new $otp_class)->send($to, $from, $text);
        } else {
            return;
        }
    }
}
