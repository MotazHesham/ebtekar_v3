@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.country.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.countries.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.country.fields.id') }}
                        </th>
                        <td>
                            {{ $country->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.country.fields.name') }}
                        </th>
                        <td>
                            {{ $country->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.country.fields.cost') }}
                        </th>
                        <td>
                            {{ $country->cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.country.fields.code') }}
                        </th>
                        <td>
                            {{ $country->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.country.fields.code_cost') }}
                        </th>
                        <td>
                            {{ $country->code_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.country.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\Country::TYPE_SELECT[$country->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.country.fields.status') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $country->status ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.country.fields.website') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $country->website ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.countries.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection