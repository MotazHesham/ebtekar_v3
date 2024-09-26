@extends('layouts.admin')
@section('content')
    @can('expense_category_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.expense-categories.create') }}">
                    {{ __('global.add') }} {{ __('cruds.expenseCategory.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ __('cruds.expenseCategory.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-ExpenseCategory">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ __('cruds.expenseCategory.fields.id') }}
                            </th>
                            <th>
                                {{ __('cruds.expenseCategory.fields.name') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenseCategories as $key => $expenseCategory)
                            <tr data-entry-id="{{ $expenseCategory->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $expenseCategory->id ?? '' }}
                                </td>
                                <td>
                                    {{ $expenseCategory->name ?? '' }}
                                </td>
                                <td>
                                    @if(!in_array($expenseCategory->id,[1,2]))
                                    
                                        @can('expense_category_show')
                                            <a class="btn btn-xs btn-primary"
                                                href="{{ route('admin.expense-categories.show', $expenseCategory->id) }}">
                                                {{ __('global.view') }}
                                            </a>
                                        @endcan

                                        @can('expense_category_edit')
                                            <a class="btn btn-xs btn-info"
                                                href="{{ route('admin.expense-categories.edit', $expenseCategory->id) }}">
                                                {{ __('global.edit') }}
                                            </a>
                                        @endcan

                                        @can('expense_category_delete')
                                            <form action="{{ route('admin.expense-categories.destroy', $expenseCategory->id) }}"
                                                method="POST" onsubmit="return confirm('{{ __('global.areYouSure') }}');"
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
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons) 

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-ExpenseCategory:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
