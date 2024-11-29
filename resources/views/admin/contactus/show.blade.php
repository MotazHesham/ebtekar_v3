@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.contactu.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.contactus.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.contactu.fields.id') }}
                        </th>
                        <td>
                            {{ $contactu->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.contactu.fields.first_name') }}
                        </th>
                        <td>
                            {{ $contactu->first_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.contactu.fields.last_name') }}
                        </th>
                        <td>
                            {{ $contactu->last_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.contactu.fields.email') }}
                        </th>
                        <td>
                            {{ $contactu->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.contactu.fields.phone_number') }}
                        </th>
                        <td>
                            {{ $contactu->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.contactu.fields.message') }}
                        </th>
                        <td>
                            {{ $contactu->message }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.contactu.fields.created_at') }}
                        </th>
                        <td>
                            {{ $contactu->created_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.contactus.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection