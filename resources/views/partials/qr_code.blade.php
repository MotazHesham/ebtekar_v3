<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"> <b>{{ $order_num }}</b> </h5> 
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body text-center">
        @production
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(500)->generate($order_num)) !!} ">
        @else
            {!! QrCode::size(500)->generate($order_num) !!}
        @endproduction

        <br>
        <br>
        <br>
        <br>
        <br>
        
        @php  
            echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($bar_code, config('app.barcode_type')) . '" alt="barcode"   />';
        @endphp
    </div>
</div>