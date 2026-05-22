@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">{{ __('global.edit') }} {{ __('cruds.shippingPartner.title_singular') }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.shipping-partners.update', $shippingPartner) }}">
            @csrf
            @method('PUT')
            @include('shipping::admin.shippingPartners.partials.form', ['shippingPartner' => $shippingPartner])
            <button class="btn btn-danger" type="submit">{{ __('global.update') }}</button>
        </form>
    </div>
</div>
@endsection
