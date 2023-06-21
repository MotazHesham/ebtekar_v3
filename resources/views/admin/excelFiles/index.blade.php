@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
          {{ trans('global.list') }} {{ trans('cruds.excelFile.title_singular') }} 
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ExcelFile">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.excelFile.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.excelFile.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.excelFile.fields.uploaded_file') }}
                        </th>
                        <th>
                            {{ trans('cruds.excelFile.fields.result_file') }}
                        </th>
                        <th>
                            {{ trans('cruds.excelFile.fields.results') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons) 

            let dtOverrideGlobals = {
                buttons: null,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.excel-files.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'uploaded_file',
                        name: 'uploaded_file',
                        sortable: false,
                        searchable: false
                    },
                    {
                        data: 'result_file',
                        name: 'result_file',
                        sortable: false,
                        searchable: false
                    },
                    {
                        data: 'results',
                        name: 'results'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            };
            let table = $('.datatable-ExcelFile').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
