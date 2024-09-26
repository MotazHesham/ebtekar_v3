@extends('layouts.admin')
@section('content')
    @can('design_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.designs.create') }}">
                    {{ __('global.add') }} {{ __('cruds.design.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ __('cruds.design.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Designe">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.design.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.design.fields.design_name') }}
                        </th>
                        <th>
                            {{ __('cruds.design.fields.profit') }}
                        </th>
                        <th>
                            {{ __('cruds.design.extra.profits') }}
                        </th>
                        <th>
                            {{ __('cruds.design.fields.status') }}
                        </th> 
                        <th>
                            {{ __('cruds.design.fields.user') }}
                        </th>
                        <th>
                            {{ __('cruds.design.fields.mockup') }}
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
        function design_images(id){ 
            $.post('{{ route('admin.desgins.design_images') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);
            }); 
        }
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons) 

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.designs.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'design_name',
                        name: 'design_name'
                    },
                    {
                        data: 'profit',
                        name: 'profit'
                    },
                    {
                        data: 'profits',
                        name: 'profits'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    }, 
                    {
                        data: 'user_name',
                        name: 'user.name'
                    },
                    {
                        data: 'mockup_name',
                        name: 'mockup.name'
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
            let table = $('.datatable-Designe').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
