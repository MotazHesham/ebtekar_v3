@extends('layouts.admin')
@section('content')
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="text-value-lg">{{ $dashboardStats['today_received'] }}</div>
                    <div>{{ __('delivery.dashboard.today_received') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="text-value-lg">{{ $dashboardStats['today_delivered'] }}</div>
                    <div>{{ __('delivery.dashboard.today_delivered') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="text-value-lg">{{ $dashboardStats['on_delivery'] }}</div>
                    <div>{{ __('delivery.dashboard.on_delivery') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="text-value-lg">{{ $dashboardStats['today_returns'] }}</div>
                    <div>{{ __('delivery.dashboard.today_returns') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form id="delivery-filters" class="form-row">
                <div class="col-md-2">
                    <select name="status" class="form-control filter-input">
                        <option value="">{{ __('cruds.deliveryOrder.fields.status') }}</option>
                        @foreach (\Modules\Shipping\Enums\ShipmentStatus::values() as $statusKey)
                            <option value="{{ $statusKey }}">{{ __('delivery_order_status.' . $statusKey) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="shipping_partner_id" class="form-control filter-input">
                        <option value="">{{ __('cruds.deliveryOrder.fields.shipping_partner') }}</option>
                        @foreach ($shippingPartners as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="deliver_man_id" class="form-control filter-input">
                        <option value="">{{ __('cruds.deliveryOrder.fields.courier') }}</option>
                        @foreach ($deliverMen as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control filter-input" placeholder="From">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control filter-input" placeholder="To">
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ __('cruds.deliveryOrder.title') }} {{ __('global.list') }}
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-DeliveryOrder">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ __('cruds.deliveryOrder.fields.order_num') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.client_name') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.status') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.shipping_partner') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.courier') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.remaining_cod') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.last_status_at') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.pending_since') }}</th>
                        <th>&nbsp;</th>
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
            @can('delivery_order_delete')
                let deleteButton = {
                    text: '{{ __('global.datatables.delete') }}',
                    url: "{{ route('admin.delivery-orders.massDestroy') }}",
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
                            }).done(function() {
                                location.reload()
                            })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            let table = $('.datatable-DeliveryOrder').DataTable({
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: {
                    url: "{{ route('admin.delivery-orders.index') }}",
                    data: function(d) {
                        $('.filter-input').each(function() {
                            d[$(this).attr('name')] = $(this).val();
                        });
                    }
                },
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'order_num',
                        name: 'order_num'
                    },
                    {
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'partner_name',
                        name: 'shipping_partner.name'
                    },
                    {
                        data: 'courier_name',
                        name: 'deliver_man.user.name'
                    },
                    {
                        data: 'remaining_cod',
                        name: 'remaining_cod'
                    },
                    {
                        data: 'last_status_at',
                        name: 'last_status_at'
                    },
                    {
                        data: 'pending_since',
                        name: 'last_status_at',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                orderCellsTop: true,
                order: [
                    [7, 'desc']
                ],
                pageLength: 25,
            });

            $('.filter-input').on('change', function() {
                table.draw();
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        })
    </script>
@endsection
