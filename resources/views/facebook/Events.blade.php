<!-- Facebook Pixel Code -->
<script> 

    // Track custom events when DOM is ready
    @if(isset($eventData))
        document.addEventListener('DOMContentLoaded', function() {
            const eventData = {
                content_name: '{{ $eventData['content_name'] }}',
                content_ids: {!! json_encode($eventData['content_ids']) !!},
                content_type: '{{ $eventData['content_type'] }}',
                @if(isset($eventData['content_category']))
                content_category: '{{ $eventData['content_category'] }}',
                @endif
                value: {{ $eventData['value'] ?? 0 }},
                currency: '{{ $eventData['currency'] ?? 'EGP' }}'
            };

            // Try Facebook Pixel first
            if (typeof fbq !== 'undefined') {
                fbq('track', '{{ $eventData['event'] }}', eventData);
                
                // Debug output
                console.log('FB Pixel event tracked:', {
                    event: '{{ $eventData['event'] }}',
                    data: eventData
                });
            }
        });
    @endif 
</script> 