@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">{{ __('global.create') }} {{ __('cruds.shippingPartner.title_singular') }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.shipping-partners.store') }}">
            @csrf
            @include('shipping::admin.shippingPartners.partials.form')
            <button class="btn btn-danger" type="submit">{{ __('global.save') }}</button>
        </form>
    </div>
</div>
@endsection
