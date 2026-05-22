@extends('layouts.admin')

@section('content')
    <div class="row mb-2">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.returns.create') }}">
                {{ __('returns::actions.register') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">{{ __('returns::titles.list') }}</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Return">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('cruds.deliveryOrder.fields.order_num') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.courier') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.return_reason') }}</th>
                        <th>{{ __('returns::fields.case_status') }}</th>
                        <th>{{ __('global.created_at') }}</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function () {
            $('.datatable-Return').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.returns.index') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'order_num', name: 'shipment.order_num', orderable: false },
                    { data: 'courier_name', name: 'courier.user.name', orderable: false },
                    { data: 'reason', name: 'reason' },
                    { data: 'status', name: 'status', orderable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']]
            });
        });
    </script>
@endsection
