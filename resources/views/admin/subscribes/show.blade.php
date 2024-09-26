@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.subscribe.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.subscribes.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.subscribe.fields.id') }}
                        </th>
                        <td>
                            {{ $subscribe->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subscribe.fields.name') }}
                        </th>
                        <td>
                            {{ $subscribe->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subscribe.fields.email') }}
                        </th>
                        <td>
                            {{ $subscribe->email }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.subscribes.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection