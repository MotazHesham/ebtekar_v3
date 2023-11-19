@extends('layouts.admin')
@section('content')
    @can('income_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.incomes.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.income.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.income.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Income">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.income.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.income.fields.income_category') }}
                            </th>
                            <th>
                                {{ trans('cruds.income.fields.entry_date') }}
                            </th>
                            <th>
                                {{ trans('cruds.income.fields.amount') }}
                            </th>
                            <th>
                                {{ trans('cruds.income.fields.description') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incomes as $key => $income)
                            <tr data-entry-id="{{ $income->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $income->id ?? '' }}
                                </td>
                                <td>
                                    {{ $income->income_category->name ?? '' }}
                                </td>
                                <td>
                                    {{ $income->entry_date ?? '' }}
                                </td>
                                <td>
                                    {{ $income->amount ?? '' }}
                                </td>
                                <td>
                                    {{ $income->description ?? '' }}
                                </td>
                                <td>
                                    @if(!$income->model_type)
                                        @can('income_show')
                                            <a class="btn btn-xs btn-primary"
                                                href="{{ route('admin.incomes.show', $income->id) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        @endcan

                                        @can('income_edit')
                                            <a class="btn btn-xs btn-info" href="{{ route('admin.incomes.edit', $income->id) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endcan

                                        @can('income_delete')
                                            <form action="{{ route('admin.incomes.destroy', $income->id) }}" method="POST"
                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-xs btn-danger"
                                                    value="{{ trans('global.delete') }}">
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
            let table = $('.datatable-Income:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
