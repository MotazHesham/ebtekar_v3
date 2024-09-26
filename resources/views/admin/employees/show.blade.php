@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    {{ __('global.show') }} {{ __('cruds.employee.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.employees.index') }}">
                                {{ __('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ __('cruds.employee.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $employee->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.employee.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $employee->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.employee.fields.email') }}
                                    </th>
                                    <td>
                                        {{ $employee->email }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.employee.fields.phone_number') }}
                                    </th>
                                    <td>
                                        {{ $employee->phone_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.employee.fields.salery') }}
                                    </th>
                                    <td>
                                        {{ $employee->salery }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.employee.fields.address') }}
                                    </th>
                                    <td>
                                        {{ $employee->address }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.employee.fields.job_description') }}
                                    </th>
                                    <td>
                                        {{ $employee->job_description }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.employees.index') }}">
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
                        <a class="nav-link active" href="#employee_employee_financials" role="tab" data-toggle="tab">
                            {{ __('cruds.employeeFinancial.title') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#employee_employee_expenses" role="tab" data-toggle="tab">
                            صرف الراتب 
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" id="employee_employee_financials">
                        @includeIf('admin.employees.relationships.employeeEmployeeFinancials', [
                            'employeeFinancials' => $employee->employeeEmployeeFinancials,
                        ])
                    </div>
                    <div class="tab-pane" role="tabpanel" id="employee_employee_expenses">
                        @includeIf('admin.employees.relationships.expenses', [
                            'expenses' => $employee->expenses,
                        ])
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
