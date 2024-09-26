@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.qualityResponsible.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.quality-responsibles.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.qualityResponsible.fields.id') }}
                        </th>
                        <td>
                            {{ $qualityResponsible->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.qualityResponsible.fields.name') }}
                        </th>
                        <td>
                            {{ $qualityResponsible->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.qualityResponsible.fields.photo') }}
                        </th>
                        <td>
                            @if($qualityResponsible->photo)
                                <a href="{{ $qualityResponsible->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $qualityResponsible->photo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.qualityResponsible.fields.phone_number') }}
                        </th>
                        <td>
                            {{ $qualityResponsible->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.qualityResponsible.fields.wts_phone') }}
                        </th>
                        <td>
                            {{ $qualityResponsible->wts_phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.qualityResponsible.fields.country_code') }}
                        </th>
                        <td>
                            {{ $qualityResponsible->country_code }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.quality-responsibles.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection