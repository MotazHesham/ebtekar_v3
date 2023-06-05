@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.borrow.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.borrows.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.borrow.fields.id') }}
                        </th>
                        <td>
                            {{ $borrow->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.borrow.fields.employee') }}
                        </th>
                        <td>
                            {{ $borrow->employee->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.borrow.fields.amount') }}
                        </th>
                        <td>
                            {{ $borrow->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.borrow.fields.status') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $borrow->status ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.borrows.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection