@extends('layouts.admin')
@section('content')
    @can('expense_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.expenses.create') }}">
                    {{ __('global.add') }} {{ __('cruds.expense.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ __('cruds.expense.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Expense">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ __('cruds.expense.fields.id') }}
                            </th>
                            <th>
                                {{ __('cruds.expense.fields.expense_category') }}
                            </th>
                            <th>
                                {{ __('cruds.expense.fields.entry_date') }}
                            </th>
                            <th>
                                {{ __('cruds.expense.fields.amount') }}
                            </th>
                            <th>
                                {{ __('cruds.expense.fields.description') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $key => $expense)
                            <tr data-entry-id="{{ $expense->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $expense->id ?? '' }}
                                </td>
                                <td>
                                    {{ $expense->expense_category->name ?? '' }}
                                </td>
                                <td>
                                    {{ $expense->entry_date ?? '' }}
                                </td>
                                <td>
                                    {{ $expense->amount ?? '' }}
                                </td>
                                <td>
                                    {!! $expense->description ?? '' !!}
                                </td>
                                <td>
                                    @if (!$expense->model_type)
                                        @can('expense_show')
                                            <a class="btn btn-xs btn-primary"
                                                href="{{ route('admin.expenses.show', $expense->id) }}">
                                                {{ __('global.view') }}
                                            </a>
                                        @endcan

                                        @can('expense_edit')
                                            <a class="btn btn-xs btn-info"
                                                href="{{ route('admin.expenses.edit', $expense->id) }}">
                                                {{ __('global.edit') }}
                                            </a>
                                        @endcan

                                        @can('expense_delete')
                                            <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST"
                                                onsubmit="return confirm('{{ __('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-xs btn-danger"
                                                    value="{{ __('global.delete') }}">
                                            </form>
                                        @endcan
                                    @endif

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $expenses->link() }}
        </div>
    </div>
@endsection 
