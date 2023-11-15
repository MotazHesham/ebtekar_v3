@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.rBranch.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.r-branches.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rBranch.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $rBranch->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rBranch.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $rBranch->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rBranch.fields.phone_number') }}
                                    </th>
                                    <td>
                                        {{ $rBranch->phone_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rBranch.fields.remaining') }}
                                    </th>
                                    <td>
                                        {{ $rBranch->remaining }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rBranch.fields.payment_type') }}
                                    </th>
                                    <td>
                                        {{ App\Models\RBranch::PAYMENT_TYPE_SELECT[$rBranch->payment_type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rBranch.fields.r_client') }}
                                    </th>
                                    <td>
                                        {{ $rBranch->r_client->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.r-branches.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8"> 
            @if($rBranch->payment_type == 'parts' && $rBranch->r_client->manage_type == 'seperate')
                <div class="card"> 
                    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#r_branche_incomes" role="tab" data-toggle="tab">
                                الدفعات
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" id="r_branche_incomes">
                            @includeIf('admin.rBranches.relationships.incomes', [
                                'incomes' => $rBranch->incomes,
                            ])
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
