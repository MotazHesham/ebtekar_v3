@extends('layouts.admin')
@section('content') 
    <div class="card">
        <div class="card-header">
            {{ __('cruds.commissionRequest.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-CommissionRequest">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.commissionRequest.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.commissionRequest.fields.status') }}
                        </th> 
                        <th>
                            {{ __('cruds.commissionRequest.fields.payment_method') }}
                        </th>
                        <th>
                            {{ __('cruds.commissionRequest.fields.transfer_number') }}
                        </th> 
                        <th>
                            {{ __('cruds.commissionRequest.fields.user') }}
                        </th> 
                        <th>
                            {{ __('cruds.commissionRequest.extra.orders') }}
                        </th> 
                        <th>
                            {{ __('cruds.commissionRequest.extra.done') }}
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
                ajax: "{{ route('admin.commission-requests.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    }, 
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'transfer_number',
                        name: 'transfer_number'
                    }, 
                    {
                        data: 'user_name',
                        name: 'user.name'
                    }, 
                    {
                        data: 'orders',
                        name: 'orders'
                    }, 
                    {
                        data: 'done',
                        name: 'done'
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
            let table = $('.datatable-CommissionRequest').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
