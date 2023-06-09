@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.designer.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.designers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.designer.fields.id') }}
                        </th>
                        <td>
                            {{ $designer->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.designer.fields.user') }}
                        </th>
                        <td>
                            {{ $designer->user }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.designer.fields.store_name') }}
                        </th>
                        <td>
                            {{ $designer->store_name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.designers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection