@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                {{ trans('global.show') }} {{ trans('cruds.rClient.title') }}
            </div>
        
            <div class="card-body">
                <div class="form-group">
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.r-clients.index') }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    {{ trans('cruds.rClient.fields.id') }}
                                </th>
                                <td>
                                    {{ $rClient->id }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.rClient.fields.name') }}
                                </th>
                                <td>
                                    {{ $rClient->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.rClient.fields.remaining') }}
                                </th>
                                <td>
                                    {{ $rClient->remaining }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.rClient.fields.phone_number') }}
                                </th>
                                <td>
                                    {{ $rClient->phone_number }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.rClient.fields.manage_type') }}
                                </th>
                                <td>
                                    {{ App\Models\RClient::MANAGE_TYPE_SELECT[$rClient->manage_type] ?? '' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.r-clients.index') }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                {{ trans('global.relatedData') }}
            </div>
            <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                <li class="nav-item">
                    <a class="nav-link " href="#r_client_r_branches" role="tab" data-toggle="tab">
                        {{ trans('cruds.rBranch.title') }}
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link active" href="#r_client_incomes" role="tab" data-toggle="tab">
                        الدفعات
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane " role="tabpanel" id="r_client_r_branches">
                    @includeIf('admin.rClients.relationships.rClientRBranches', ['rBranches' => $rClient->rClientRBranches])
                </div>
                <div class="tab-pane active" role="tabpanel" id="r_client_incomes">
                    @includeIf('admin.rClients.relationships.incomes', [
                        'incomes' => $rClient->incomes,
                    ])
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection