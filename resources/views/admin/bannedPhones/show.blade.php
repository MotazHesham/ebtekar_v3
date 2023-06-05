@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.bannedPhone.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.banned-phones.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.bannedPhone.fields.id') }}
                        </th>
                        <td>
                            {{ $bannedPhone->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bannedPhone.fields.phone') }}
                        </th>
                        <td>
                            {{ $bannedPhone->phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bannedPhone.fields.reason') }}
                        </th>
                        <td>
                            {{ $bannedPhone->reason }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.banned-phones.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection