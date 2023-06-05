@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.designe.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.designes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.designe.fields.id') }}
                        </th>
                        <td>
                            {{ $designe->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.designe.fields.design_name') }}
                        </th>
                        <td>
                            {{ $designe->design_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.designe.fields.profit') }}
                        </th>
                        <td>
                            {{ $designe->profit }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.designe.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Designe::STATUS_SELECT[$designe->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.designe.fields.cancel_reason') }}
                        </th>
                        <td>
                            {{ $designe->cancel_reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.designe.fields.user') }}
                        </th>
                        <td>
                            {{ $designe->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.designe.fields.mockup') }}
                        </th>
                        <td>
                            {{ $designe->mockup->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.designes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection