@extends('layouts.admin')
@section('content')
    @can('country_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.countries.create') }}">
                    {{ __('global.add') }} {{ __('cruds.country.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ __('cruds.country.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Country">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.country.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.country.fields.name') }}
                        </th>
                        <th>
                            {{ __('cruds.country.fields.cost') }}
                        </th>
                        <th>
                            {{ __('cruds.country.fields.code') }}
                        </th>
                        <th>
                            {{ __('cruds.country.fields.code_cost') }}
                        </th>
                        <th>
                            {{ __('cruds.country.fields.type') }}
                        </th>
                        <th>
                            {{ __('cruds.country.fields.status') }}
                        </th>
                        <th>
                            {{ __('cruds.country.fields.website') }}
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
        
        function update_statuses(el,type){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.countries.update_statuses') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status, type:type}, function(data){
                if(data == 1){
                    showAlert('success', 'Success', '');
                }else{
                    showAlert('danger', 'Something went wrong', '');
                }
            });
        }
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('country_delete')
                let deleteButtonTrans = '{{ __('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.countries.massDestroy') }}",
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
                ajax: "{{ route('admin.countries.index') }}",
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
                        data: 'cost',
                        name: 'cost'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'code_cost',
                        name: 'code_cost'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'website',
                        name: 'website'
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
            let table = $('.datatable-Country').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
