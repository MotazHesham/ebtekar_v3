<div class="row text-center mt-3">
    <div class="col-md-2">
        <span class="badge badge-info">{{ trans('cruds.receiptSocial.title') }}</span>
        <br>
        <b> {{ $receipt_social }} </b>
    </div> 
    <div class="col-md-2">
        <span class="badge badge-info">{{ trans('cruds.receiptCompany.title') }}</span>
        <br>
        <b> {{ $receipt_company }} </b>
    </div>
    <div class="col-md-2">
        <span class="badge badge-info">{{ trans('cruds.receiptClient.title') }}</span>
        <br>
        <b> {{ $receipt_client }} </b>
    </div>
    <div class="col-md-2">
        <span class="badge badge-info">{{ trans('cruds.order.extra.customer_orders') }}</span>
        <br>
        <b> {{ $customers_orders }} </b>
    </div>
    <div class="col-md-2">
        <span class="badge badge-info">{{ trans('cruds.order.extra.seller_orders') }}</span>
        <br>
        <b> {{ $sellers_orders }} </b>
    </div>
    @if($banned_phones)
        <div class="col-md-2">
            <span class="badge badge-danger">{{ trans('cruds.bannedPhone.title_singular') }}</span> 
            <br>
            <b> {{ $banned_phones->reason }} </b>
        </div>
    @endif
</div>
