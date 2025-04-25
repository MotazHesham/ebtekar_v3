@php
    $advancedMatching = auth()->check() ? [
        'external_id' => auth()->id(),
        'em' => auth()->user()->hashedEmail(),
        'ph' => auth()->user()->hashedPhone(),
        'fn' => auth()->user()->hashedFirstName(),
        'ln' => auth()->user()->hashedLastName(),
        'country' => hashedForConversionApi(session("country_code",'EG')),
        'st' => getHashedStateForCAPI(),
    ] : [];

    $options = ['agent' => getFbp()];
    if (getFbc()) {
        $options['fbc'] = getFbc();
    }
@endphp
<!-- Facebook Pixel Code -->
<script>
    // Load Facebook Pixel asynchronously
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    
    // Initialize Pixel with enhanced parameters
    
    fbq('init', '{{ $pixelId }}', @json($advancedMatching), @json($options)); 

    // Track PageView immediately
    fbq('track', 'PageView');  
</script>

<!-- Noscript fallback -->
<noscript>
    <img height="1" width="1" style="display:none" 
        src="https://www.facebook.com/tr?id={{ $pixelId }}&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->