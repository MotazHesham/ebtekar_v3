@extends('layouts.admin')
@section('content')
    @can('income_category_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.income-categories.create') }}">
                    {{ __('global.add') }} {{ __('cruds.incomeCategory.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ __('cruds.incomeCategory.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-IncomeCategory">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ __('cruds.incomeCategory.fields.id') }}
                            </th>
                            <th>
                                {{ __('cruds.incomeCategory.fields.name') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incomeCategories as $key => $incomeCategory)
                            <tr data-entry-id="{{ $incomeCategory->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $incomeCategory->id ?? '' }}
                                </td>
                                <td>
                                    {{ $incomeCategory->name ?? '' }}
                                </td>
                                <td>
                                    @if(!in_array($incomeCategory->id,[1,2,3,4,5,6]))
                                        @can('income_category_show')
                                            <a class="btn btn-xs btn-primary"
                                                href="{{ route('admin.income-categories.show', $incomeCategory->id) }}">
                                                {{ __('global.view') }}
                                            </a>
                                        @endcan

                                        @can('income_category_edit')
                                            <a class="btn btn-xs btn-info"
                                                href="{{ route('admin.income-categories.edit', $incomeCategory->id) }}">
                                                {{ __('global.edit') }}
                                            </a>
                                        @endcan

                                        @can('income_category_delete')
                                            <form action="{{ route('admin.income-categories.destroy', $incomeCategory->id) }}"
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
            let table = $('.datatable-IncomeCategory:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
