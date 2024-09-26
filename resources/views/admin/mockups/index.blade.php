@extends('layouts.admin')
@section('content')
    @can('mockup_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.mockups.create') }}">
                    {{ __('global.add') }} {{ __('cruds.mockup.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ __('cruds.mockup.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Mockup">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.mockup.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.mockup.fields.name') }}
                        </th>
                        <th>
                            {{ __('cruds.mockup.fields.preview_1') }}
                        </th>
                        <th>
                            {{ __('cruds.mockup.fields.purchase_price') }}
                        </th>
                        <th>
                            {{ __('cruds.mockup.fields.colors') }}
                        </th>
                        <th>
                            {{ __('cruds.mockup.fields.attribute_options') }}
                        </th>
                        <th>
                            {{ __('cruds.mockup.fields.category') }}
                        </th>
                        <th>
                            {{ __('cruds.mockup.fields.sub_category') }}
                        </th>
                        <th>
                            {{ __('cruds.mockup.fields.sub_sub_category') }}
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
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.mockups.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'preview_1',
                        name: 'preview_1',
                        sortable: false,
                        searchable: false
                    },
                    {
                        data: 'purchase_price',
                        name: 'purchase_price'
                    },
                    {
                        data: 'colors',
                        name: 'colors'
                    },
                    {
                        data: 'attribute_options',
                        name: 'attribute_options'
                    },
                    {
                        data: 'category_name',
                        name: 'category.name'
                    },
                    {
                        data: 'sub_category_name',
                        name: 'sub_category.name'
                    },
                    {
                        data: 'sub_sub_category_name',
                        name: 'sub_sub_category.name'
                    },
                    {
                        data: 'actions',
                        name: '{{ __('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            };
            let table = $('.datatable-Mockup').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
