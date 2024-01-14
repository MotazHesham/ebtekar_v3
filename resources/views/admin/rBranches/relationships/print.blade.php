<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Qr</title>
</head>
<body>
    <h5>{{ $qr_product_key->name ?? '' }}</h5>
    @production
    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(250)->generate('https://ebtekarstore.com?id='.$id)) !!} ">
    @else
        {!! QrCode::size(250)->generate('https://ebtekarstore.com?id='.$id) !!}
    @endproduction
</body>
</html>