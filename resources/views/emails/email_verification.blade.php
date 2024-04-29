<h4>Hello!</h4>
<p>لإنهاء التسجيل - يطلب منك الموقع التحقق من بريدك الإلكتروني</p>
<a href="{{route('userVerification', $user->verification_token)}}">أضغط هنا للتحقق</a>

<p>
    شكرا لزيارتك لموقعنا
    <br>
    Regards,
    {{ $site_settings->site_name }}
</p> 