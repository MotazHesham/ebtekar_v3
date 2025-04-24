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
    
    // Initialize Pixel with additional parameters
    fbq('init', '{{ $pixelId }}', {
        @if(isset($eventData['external_id']))
        external_id: '{{ $eventData['external_id'] }}',
        @endif
    }, {agent: '{{ $fbp }}'});
    
    // Track PageView immediately
    fbq('track', 'PageView');

    // Helper function to get cookies
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    // Track custom events when DOM is ready
    @if(isset($eventData))
    document.addEventListener('DOMContentLoaded', function() {
        const eventData = {
            content_name: '{{ $eventData['content_name'] }}',
            content_ids: {{ json_encode($eventData['content_ids']) }},
            content_type: '{{ $eventData['content_type'] }}',
            @if(isset($eventData['value']))
            value: {{ $eventData['value'] }},
            currency: '{{ $eventData['currency'] }}',
            @endif
        };

        // Try Facebook Pixel first
        if (typeof fbq !== 'undefined') {
            fbq('track', '{{ $eventData['event'] }}', eventData);
        }
    });
    @endif

    // Fallback for ad blockers (after 1 second)
    setTimeout(function() {
        @if(isset($eventData))
        if (typeof fbq === 'undefined') {
            const fallbackData = {
                event: '{{ $eventData['event'] }}',
                data: {
                    content_name: '{{ $eventData['content_name'] }}',
                    content_ids: {{ json_encode($eventData['content_ids']) }},
                    content_type: '{{ $eventData['content_type'] }}',
                    @if(isset($eventData['value']))
                    value: {{ $eventData['value'] }},
                    currency: '{{ $eventData['currency'] }}',
                    @endif
                },
                fbp: getCookie('_fbp'),
                fbc: getCookie('_fbc')
            };

            // Send to your Laravel endpoint
            fetch('/api/track-event', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(fallbackData)
            }).catch(err => console.error('FB Pixel fallback failed:', err));
        }
        @endif
    }, 1000);
</script>

<!-- Noscript fallback -->
<noscript>
    <img height="1" width="1" style="display:none" 
        src="https://www.facebook.com/tr?id={{ $pixelId }}&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->