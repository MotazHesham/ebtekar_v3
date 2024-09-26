@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.expense.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.expenses.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.expense.fields.id') }}
                        </th>
                        <td>
                            {{ $expense->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.expense.fields.expense_category') }}
                        </th>
                        <td>
                            {{ $expense->expense_category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.expense.fields.entry_date') }}
                        </th>
                        <td>
                            {{ $expense->entry_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.expense.fields.amount') }}
                        </th>
                        <td>
                            {{ $expense->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.expense.fields.description') }}
                        </th>
                        <td>
                            {{ $expense->description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.expenses.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection