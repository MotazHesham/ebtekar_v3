<h4>Hello!</h4>
<p>لإنهاء التسجيل - يطلب منك الموقع التحقق من بريدك الإلكتروني</p>
<a href="{{route('userVerification', $user->verification_token)}}">أضغط هنا للتحقق</a>

<p>
    شكرا لزيارتك لموقعنا
    <br>
    Regards,
    {{ $site_settings->site_name }}
</p>

<hr>

<small>
    If you're having trouble clicking the "اضغط هنا للتحقق" button, copy and paste the URL below into your web browser:
</small> 

<a href="{{route('userVerification', $user->verification_token)}}">{{route('userVerification', $user->verification_token)}}</a>