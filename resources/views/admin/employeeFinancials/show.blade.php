@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.employeeFinancial.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.employee-financials.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.employeeFinancial.fields.id') }}
                        </th>
                        <td>
                            {{ $employeeFinancial->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.employeeFinancial.fields.employee') }}
                        </th>
                        <td>
                            {{ $employeeFinancial->employee->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.employeeFinancial.fields.financial_category') }}
                        </th>
                        <td>
                            {{ $employeeFinancial->financial_category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.employeeFinancial.fields.amount') }}
                        </th>
                        <td>
                            {{ $employeeFinancial->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.employeeFinancial.fields.reason') }}
                        </th>
                        <td>
                            {{ $employeeFinancial->reason }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.employee-financials.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection