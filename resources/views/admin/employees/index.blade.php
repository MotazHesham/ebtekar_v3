@extends('layouts.admin')
@section('content')
    @can('employee_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.employees.create') }}">
                    {{ __('global.add') }} {{ __('cruds.employee.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ __('cruds.employee.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Employee">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.employee.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.employee.fields.name') }}
                        </th>
                        <th>
                            {{ __('cruds.employee.fields.email') }}
                        </th>
                        <th>
                            {{ __('cruds.employee.fields.phone_number') }}
                        </th>
                        <th>
                            {{ __('cruds.employee.fields.salery') }}
                        </th>
                        <th>
                            {{ __('cruds.employee.fields.address') }}
                        </th>
                        <th>
                            {{ __('cruds.employee.fields.job_description') }}
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
            @can('employee_delete')
                let deleteButtonTrans = '{{ __('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.employees.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            alert('{{ __('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ __('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.employees.index') }}",
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
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'salery',
                        name: 'salery'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'job_description',
                        name: 'job_description'
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
            let table = $('.datatable-Employee').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
