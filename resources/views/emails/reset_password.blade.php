<h4>Hello!</h4>
<p>You are receiving this email because we received a password reset request for your account.</p>
<a href="{{ $url }}">Reset Password</a>

<p>
    This password reset link will expire in 60 minutes.

    If you did not request a password reset, no further action is required.
    
    <br>
    Regards,
    {{ $site_settings->site_name }}
</p>

<hr>

<small>
    If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
</small> 

<a href="{{ $url }}">{{ $url }}</a>