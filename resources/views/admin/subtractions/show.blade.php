@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.subtraction.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.subtractions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.subtraction.fields.id') }}
                        </th>
                        <td>
                            {{ $subtraction->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subtraction.fields.employee') }}
                        </th>
                        <td>
                            {{ $subtraction->employee->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subtraction.fields.amount') }}
                        </th>
                        <td>
                            {{ $subtraction->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subtraction.fields.reason') }}
                        </th>
                        <td>
                            {{ $subtraction->reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subtraction.fields.status') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $subtraction->status ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.subtractions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection