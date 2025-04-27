@php
    $advancedMatching = [
        'external_id' => getHashedExternalIdForCAPI(),
        'country' => getHashedCountryForCAPI(),
        'st' => getHashedStateForCAPI(),
        'ct' => getHashedCityForCAPI(),
    ];

    if (auth()->check()) {
        $advancedMatching = array_merge($advancedMatching, [
            'em' => auth()->user()->hashedEmail(),
            'ph' => auth()->user()->hashedPhone(),
            'fn' => auth()->user()->hashedFirstName(),
            'ln' => auth()->user()->hashedLastName(),
        ]);
    }

    $options = [
        'agent' => getFbp(),
        'debug' => config('app.debug'), // Enable debug in local/staging
        'cookie' => [
            'domain' => '.' . parse_url(str_replace('public','',config('app.url')), PHP_URL_HOST), // Root domain cookie
            'sameSite' => 'Lax' // Compliant with modern browsers
        ]
    ];
    if (getFbc()) {
        $options['fbc'] = getFbc();
    }
@endphp
<!-- Facebook Pixel Code -->
<script >
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

    // 3. Make metaPixelEvent available globally early
    window.metaPixelEvent = function(eventData) {
        @if(!$site_settings->fb_pixel_id)
            return;
        @endif
        // Ensure eventData is properly parsed if coming as JSON string
        const data = typeof eventData === 'string' ? JSON.parse(eventData) : eventData;
        
        const finalEventData = {};
        
        if (data.content_name) {
            finalEventData.content_name = data.content_name;
        } 
        if (data.content_ids) {
            finalEventData.content_ids = Array.isArray(data.content_ids) ? data.content_ids : [data.content_ids];
        } 
        if (data.content_type) {
            finalEventData.content_type = data.content_type;
        } 
        if (data.content_category) {
            finalEventData.content_category = data.content_category;
        } 
        if (data.num_items) {
            finalEventData.num_items = data.num_items;
        } 
        if (data.search_string) {
            finalEventData.search_string = data.search_string;
        } 
        if (data.currency) {
            finalEventData.currency = data.currency;
        } 
        if (data.value) {
            finalEventData.value = data.value;
        }  
        
        if (typeof fbq !== 'undefined') {
            fbq('track', data.event, finalEventData, {eventID: data.event_id});
            console.log('FB Pixel event tracked:', {
                event: data.event,
                data: finalEventData
            });
        } else {
            // Queue event if pixel not loaded yet
            fbq.queue.push(['track', data.event, finalEventData, {eventID: data.event_id}]);
        } 
    }(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');
</script>

<!-- Noscript fallback -->
<noscript>
    <img height="1" width="1" style="display:none" 
        src="https://www.facebook.com/tr?id={{ $pixelId }}&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->

@if(session('eventData'))
    <script> 
        metaPixelEvent(@json(session('eventData')));
    </script>
@endif

<!-- Then load the full script deferred -->
<script defer src="https://connect.facebook.net/en_US/fbevents.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () { 
        // pageview from server
        $.get('/pageview/event', function (res) { 
        });
    }); 
</script>