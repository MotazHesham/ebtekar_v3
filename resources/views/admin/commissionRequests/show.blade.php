@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.commissionRequest.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.commission-requests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.commissionRequest.fields.id') }}
                        </th>
                        <td>
                            {{ $commissionRequest->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.commissionRequest.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\CommissionRequest::STATUS_SELECT[$commissionRequest->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.commissionRequest.fields.total_commission') }}
                        </th>
                        <td>
                            {{ $commissionRequest->total_commission }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.commissionRequest.fields.payment_method') }}
                        </th>
                        <td>
                            {{ App\Models\CommissionRequest::PAYMENT_METHOD_SELECT[$commissionRequest->payment_method] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.commissionRequest.fields.transfer_number') }}
                        </th>
                        <td>
                            {{ $commissionRequest->transfer_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.commissionRequest.fields.done_time') }}
                        </th>
                        <td>
                            {{ $commissionRequest->done_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.commissionRequest.fields.user') }}
                        </th>
                        <td>
                            {{ $commissionRequest->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.commissionRequest.fields.created_by') }}
                        </th>
                        <td>
                            {{ $commissionRequest->created_by->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.commissionRequest.fields.done_by_user') }}
                        </th>
                        <td>
                            {{ $commissionRequest->done_by_user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.commission-requests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection