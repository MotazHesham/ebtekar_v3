<div class="row text-center mt-3">
    <div class="col-md-2">
        <span class="badge badge-info">{{ __('cruds.receiptSocial.title') }}</span>
        <br>
        <b> {{ $receipt_social }} </b>
        @if(isset($last_receipt_social) && $last_receipt_social)
            <div class="text-dark small">{{ $last_receipt_social->created_at }}</div>
        @endif
    </div> 
    <div class="col-md-2">
        <span class="badge badge-info">{{ __('cruds.receiptCompany.title') }}</span>
        <br>
        <b> {{ $receipt_company }} </b>
        @if(isset($last_receipt_company) && $last_receipt_company)
            <div class="text-dark small">{{ $last_receipt_company->created_at }}</div>
        @endif
    </div>
    <div class="col-md-2">
        <span class="badge badge-info">{{ __('cruds.receiptClient.title') }}</span>
        <br>
        <b> {{ $receipt_client }} </b>
        @if(isset($last_receipt_client) && $last_receipt_client)
            <div class="text-dark small">{{ $last_receipt_client->created_at }}</div>
        @endif
    </div>
    <div class="col-md-2">
        <span class="badge badge-info">{{ __('cruds.order.extra.customer_orders') }}</span>
        <br>
        <b> {{ $customers_orders }} </b>
        @if(isset($last_customer_order) && $last_customer_order)
            <div class="text-dark small">{{ $last_customer_order->created_at }}</div>
        @endif
    </div>
    <div class="col-md-2">
        <span class="badge badge-info">{{ __('cruds.order.extra.seller_orders') }}</span>
        <br>
        <b> {{ $sellers_orders }} </b>
        @if(isset($last_seller_order) && $last_seller_order)
            <div class="text-dark small">{{ $last_seller_order->created_at }}</div>
        @endif
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
