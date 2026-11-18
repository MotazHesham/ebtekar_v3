@if (!empty($oneTimePrint))
<meta name="playlist-print" content="1">
<script>
    (function () {
        var printing = false;

        function blankReceipt() {
            document.documentElement.replaceChildren();
        }

        window.addEventListener('beforeprint', function () {
            printing = true;
        });
        window.addEventListener('afterprint', function () {
            printing = false;
            blankReceipt();
        });
        window.addEventListener('pagehide', function () {
            if (!printing) {
                blankReceipt();
            }
        });
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                blankReceipt();
            }
        });
    })();
</script>
@endif
