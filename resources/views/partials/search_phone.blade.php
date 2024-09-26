<div class="row text-center mt-3">
    <div class="col-md-2">
        <span class="badge badge-info">{{ __('cruds.receiptSocial.title') }}</span>
        <br>
        <b> {{ $receipt_social }} </b>
    </div> 
    <div class="col-md-2">
        <span class="badge badge-info">{{ __('cruds.receiptCompany.title') }}</span>
        <br>
        <b> {{ $receipt_company }} </b>
    </div>
    <div class="col-md-2">
        <span class="badge badge-info">{{ __('cruds.receiptClient.title') }}</span>
        <br>
        <b> {{ $receipt_client }} </b>
    </div>
    <div class="col-md-2">
        <span class="badge badge-info">{{ __('cruds.order.extra.customer_orders') }}</span>
        <br>
        <b> {{ $customers_orders }} </b>
    </div>
    <div class="col-md-2">
        <span class="badge badge-info">{{ __('cruds.order.extra.seller_orders') }}</span>
        <br>
        <b> {{ $sellers_orders }} </b>
    </div>
    @if($banned_phones)
        <div class="col-md-2">
            <span class="badge badge-danger">{{ __('cruds.bannedPhone.title_singular') }}</span> 
            <br>
            <b> {{ $banned_phones->reason }} </b>
        </div>
    @endif
</div>
<hr>
<button type="submit" class="btn btn-success" @if($are_you_sure) onclick="return confirm('{{ __('global.areYouSure') }}');" @endif>{{ __('global.continue') }}</button>
