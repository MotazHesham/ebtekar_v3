@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.season.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <a class="btn btn-default" href="{{ route('admin.seasons.index') }}">
                {{ __('global.back_to_list') }}
            </a>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>{{ __('cruds.season.fields.id') }}</th>
                    <td>{{ $season->id }}</td>
                </tr>
                <tr>
                    <th>{{ __('cruds.season.fields.name') }}</th>
                    <td>{{ $season->name }}</td>
                </tr>
                <tr>
                    <th>{{ __('cruds.season.fields.year') }}</th>
                    <td>{{ $season->year }}</td>
                </tr>
                <tr>
                    <th>{{ __('cruds.season.fields.start_date') }}</th>
                    <td>{{ $season->start_date }}</td>
                </tr>
                <tr>
                    <th>{{ __('cruds.season.fields.end_date') }}</th>
                    <td>{{ $season->end_date }}</td>
                </tr>
            </tbody>
        </table>
        <div class="form-group">
            <a class="btn btn-default" href="{{ route('admin.seasons.index') }}">
                {{ __('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>

@endsection
