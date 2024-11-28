
<div class="g-recaptcha" data-sitekey="{{ config('app.recaptcha.site_key') }}"></div>
@if ($errors->has('g-recaptcha-response'))
    <div style="color:red">
        {{ $errors->first('g-recaptcha-response') }}
    </div>
@endif