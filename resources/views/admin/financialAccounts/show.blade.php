@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.financialAccount.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.financial-accounts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.financialAccount.fields.id') }}
                        </th>
                        <td>
                            {{ $financialAccount->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.financialAccount.fields.account') }}
                        </th>
                        <td>
                            {{ $financialAccount->account }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.financialAccount.fields.description') }}
                        </th>
                        <td>
                            {{ $financialAccount->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.financialAccount.fields.active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $financialAccount->active ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.financial-accounts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection