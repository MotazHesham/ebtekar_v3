@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.currency.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.currencies.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.id') }}
                        </th>
                        <td>
                            {{ $currency->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.name') }}
                        </th>
                        <td>
                            {{ $currency->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.symbol') }}
                        </th>
                        <td>
                            {{ $currency->symbol }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.exchange_rate') }}
                        </th>
                        <td>
                            {{ $currency->exchange_rate }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.status') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $currency->status ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.code') }}
                        </th>
                        <td>
                            {{ $currency->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.half_kg') }}
                        </th>
                        <td>
                            {{ $currency->half_kg }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.one_kg') }}
                        </th>
                        <td>
                            {{ $currency->one_kg }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.one_half_kg') }}
                        </th>
                        <td>
                            {{ $currency->one_half_kg }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.two_kg') }}
                        </th>
                        <td>
                            {{ $currency->two_kg }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.two_half_kg') }}
                        </th>
                        <td>
                            {{ $currency->two_half_kg }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.currency.fields.three_kg') }}
                        </th>
                        <td>
                            {{ $currency->three_kg }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.currencies.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection