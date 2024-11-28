@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                {{ __('global.show') }} {{ __('cruds.rClient.title') }}
            </div>
        
            <div class="card-body">
                <div class="form-group">
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.r-clients.index') }}">
                            {{ __('global.back_to_list') }}
                        </a>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    {{ __('cruds.rClient.fields.id') }}
                                </th>
                                <td>
                                    {{ $rClient->id }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ __('cruds.rClient.fields.name') }}
                                </th>
                                <td>
                                    {{ $rClient->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ __('cruds.rClient.fields.remaining') }}
                                </th>
                                <td>
                                    {{  $rClient->manage_type == 'unified' ? $rClient->remaining : 0 }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ __('cruds.rClient.fields.phone_number') }}
                                </th>
                                <td>
                                    {{ $rClient->phone_number }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ __('cruds.rClient.fields.manage_type') }}
                                </th>
                                <td>
                                    {{ App\Models\RClient::MANAGE_TYPE_SELECT[$rClient->manage_type] ?? '' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.r-clients.index') }}">
                            {{ __('global.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                {{ __('global.relatedData') }}
            </div>
            <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                <li class="nav-item">
                    <a class="nav-link " href="#r_client_r_branches" role="tab" data-toggle="tab">
                        {{ __('cruds.rBranch.title') }}
                    </a>
                </li>
                @if($rClient->manage_type == 'unified')
                    <li class="nav-item ">
                        <a class="nav-link active" href="#r_client_incomes" role="tab" data-toggle="tab">
                            الدفعات
                        </a>
                    </li>
                @endif
            </ul>
            <div class="tab-content">
                <div class="tab-pane " role="tabpanel" id="r_client_r_branches">
                    @includeIf('admin.rClients.relationships.rClientRBranches', ['rBranches' => $rClient->rClientRBranches])
                </div> 
                @if($rClient->manage_type == 'unified')
                    <div class="tab-pane active" role="tabpanel" id="r_client_incomes">
                        @if($rClient->type == 'income')
                            @includeIf('admin.rClients.relationships.incomes', [
                                'incomes' => $rClient->incomes,
                            ])
                        @else 
                            @includeIf('admin.rClients.relationships.expenses', [
                                'expenses' => $rClient->expenses,
                            ])
                        @endif
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>

@endsection