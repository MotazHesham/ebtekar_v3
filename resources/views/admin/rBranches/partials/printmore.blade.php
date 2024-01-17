<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Qr</title>
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/bootstrap.min.css') }}">
    <script src="{{ asset('dashboard_offline/js/jquery.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/bootstrap.min.js') }}"></script>
    <style>
		@page {
			size: auto;   /* auto is the initial value */
			margin: 0;  /* this affects the margin in the printer settings */
		}
    </style>
</head>
<body> 
    @foreach($qr_product_keys as $qr_product_key)
        <div style="page-break-after: always;">
            <h1 style="font-size: 4.0rem;">{{ $qr_product_key->name ?? '' }}</h1>
            @production
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)->generate('https://ebtekarstore.com?id='.$qr_product_key->id)) !!} ">
            @else
                {!! QrCode::size(300)->generate('https://ebtekarstore.com?id='.$qr_product_key->id) !!}
            @endproduction 
            <h4>{{ $qr_product_key->product->product ?? '' }}</h4> 
        </div>
    @endforeach
    <script>
        
        $(document).ready(function() {
            window.print()
        });
        
    </script>
</body>
</html>