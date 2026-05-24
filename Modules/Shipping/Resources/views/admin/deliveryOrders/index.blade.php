@extends('layouts.admin')
@section('content')
    <div class="row mb-3" id="dashboard-stats">
        <div class="col-md-2 col-6 mb-2">
            <div class="card text-white bg-primary"><div class="card-body p-2">
                <div class="text-value-lg" data-stat="today_received">{{ $dashboardStats['today_received'] }}</div>
                <small>{{ __('delivery.dashboard.today_received') }}</small>
            </div></div>
        </div>
        <div class="col-md-2 col-6 mb-2">
            <div class="card text-white bg-success"><div class="card-body p-2">
                <div class="text-value-lg" data-stat="today_delivered">{{ $dashboardStats['today_delivered'] }}</div>
                <small>{{ __('delivery.dashboard.today_delivered') }}</small>
            </div></div>
        </div>
        <div class="col-md-2 col-6 mb-2">
            <div class="card text-white bg-warning"><div class="card-body p-2">
                <div class="text-value-lg" data-stat="on_delivery">{{ $dashboardStats['on_delivery'] }}</div>
                <small>{{ __('delivery.dashboard.on_delivery') }}</small>
            </div></div>
        </div>
        <div class="col-md-2 col-6 mb-2">
            <div class="card text-white bg-danger"><div class="card-body p-2">
                <div class="text-value-lg" data-stat="today_returns">{{ $dashboardStats['today_returns'] }}</div>
                <small>{{ __('delivery.dashboard.today_returns') }}</small>
            </div></div>
        </div>
        <div class="col-md-2 col-6 mb-2">
            <div class="card bg-light"><div class="card-body p-2">
                <div class="text-value-lg" data-stat="total_cod_collect">{{ $dashboardStats['total_cod_collect'] ?? 0 }}</div>
                <small>{{ __('delivery.dashboard.total_cod_collect') }}</small>
            </div></div>
        </div>
        <div class="col-md-2 col-6 mb-2">
            <div class="card bg-light"><div class="card-body p-2">
                <div class="text-value-lg" data-stat="total_cod_collected">{{ $dashboardStats['total_cod_collected'] ?? 0 }}</div>
                <small>{{ __('delivery.dashboard.total_cod_collected') }}</small>
            </div></div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card bg-light"><div class="card-body p-2">
                <div class="text-value-lg" data-stat="total_returns_amount">{{ $dashboardStats['total_returns_amount'] ?? 0 }}</div>
                <small>{{ __('delivery.dashboard.total_returns_amount') }}</small>
            </div></div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card bg-light"><div class="card-body p-2">
                <div class="text-value-lg" data-stat="total_shipping_cost">{{ $dashboardStats['total_shipping_cost'] ?? 0 }}</div>
                <small>{{ __('delivery.dashboard.total_shipping') }}</small>
            </div></div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form id="delivery-filters" class="form-row align-items-end">
                @if($showPartnerFilter)
                <div class="col-md-2">
                    <label class="small mb-0">{{ __('cruds.deliveryOrder.fields.shipping_partner') }}</label>
                    <select name="shipping_partner_id" class="form-control filter-input">
                        <option value="">{{ __('global.all') }}</option>
                        @foreach ($shippingPartners as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if($showCourierFilter)
                <div class="col-md-2">
                    <label class="small mb-0">{{ __('cruds.deliveryOrder.fields.courier') }}</label>
                    <select name="deliver_man_id" class="form-control filter-input">
                        <option value="">{{ __('global.all') }}</option>
                        @foreach ($deliverMen as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-2">
                    <label class="small mb-0">{{ __('cruds.deliveryOrder.fields.status') }}</label>
                    <select name="status" class="form-control filter-input">
                        <option value="">{{ __('global.all') }}</option>
                        @foreach (\Modules\Shipping\Enums\ShipmentStatus::values() as $statusKey)
                            <option value="{{ $statusKey }}">{{ __('delivery_order_status.' . $statusKey) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control filter-input" placeholder="From">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control filter-input" placeholder="To">
                </div>
                @if($canExport)
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-success btn-block" id="btn-export-all">{{ __('delivery.actions.export_all') }}</button>
                </div>
                @endif
            </form>
            <div class="mt-2 alert alert-secondary py-2 mb-0">
                <strong>{{ __('delivery.list.selected_cod_total') }}:</strong>
                <span id="selected-cod-total">0.00</span>
                @if($canExport)
                    <button type="button" class="btn btn-sm btn-success ml-2" id="btn-export-selected">{{ __('delivery.actions.export_selected') }}</button>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">{{ __('cruds.deliveryOrder.title') }} {{ __('global.list') }}</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-DeliveryOrder">
                <thead>
                    <tr>
                        <th width="10"><input type="checkbox" id="select-all-rows"></th>
                        <th width="40">#</th>
                        <th>{{ __('cruds.deliveryOrder.fields.order_num') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.client_name') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.governorate') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.region') }}</th>
                        <th>{{ __('delivery.fields.full_address') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.status') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.shipping_partner') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.courier') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.remaining_cod') }}</th>
                        <th width="120"></th>
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
        $(function () {
            const confirmMsg = @json(__('delivery.messages.confirm_status'));
            const statsUrl = @json(route('admin.delivery-orders.stats'));
            const quickUrl = @json(route('admin.delivery-orders.quick-status'));
            const exportUrl = @json(route('admin.delivery-orders.export'));
            const token = @json(csrf_token());

            function refreshStats() {
                const params = {};
                $('.filter-input').each(function () {
                    if ($(this).attr('name') && $(this).val()) {
                        params[$(this).attr('name')] = $(this).val();
                    }
                });
                $.get(statsUrl, params, function (data) {
                    Object.keys(data).forEach(function (key) {
                        $('[data-stat="' + key + '"]').text(data[key]);
                    });
                });
            }

            function selectedIds() {
                return $('.shipment-row-select:checked').map(function () {
                    return $(this).val();
                }).get();
            }

            function updateCodTotal() {
                let total = 0;
                $('.shipment-row-select:checked').each(function () {
                    total += parseFloat($(this).data('cod')) || 0;
                });
                $('#selected-cod-total').text(total.toFixed(2));
            }

            let dtOverrideGlobals = {
                processing: true,
                serverSide: true,
                retrieve: true,
                select: false,
                scrollX: true,
                aaSorting: [],
                order: [[2, 'desc']],
                pageLength: 25,
                dom: 'lfrtip',
                buttons: [],
                language: {
                    @if(app()->getLocale() === 'ar')
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json'
                    @else
                    url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
                    @endif
                },
                columnDefs: [
                    { orderable: false, searchable: false, targets: [0, 1, 11, 12] }
                ],
                ajax: {
                    url: @json(route('admin.delivery-orders.index')),
                    data: function (d) {
                        $('.filter-input').each(function () {
                            d[$(this).attr('name')] = $(this).val();
                        });
                    },
                    error: function (xhr) {
                        console.error('delivery-orders DataTable', xhr.status, xhr.responseText);
                        showAlert('error', 'DataTables', xhr.status + ' ' + (xhr.responseJSON?.message || 'Request failed'));
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'order_num', name: 'order_num' },
                    { data: 'client_name', name: 'client_name' },
                    { data: 'governorate', name: 'governorate' },
                    { data: 'region', name: 'region' },
                    { data: 'full_address', name: 'full_address', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'partner_name', name: 'partner_name', orderable: false, searchable: false },
                    { data: 'courier_name', name: 'courier_name', orderable: false, searchable: false },
                    { data: 'remaining_cod', name: 'remaining_cod' },
                    { data: 'quick_actions', name: 'quick_actions', orderable: false, searchable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
            };

            let table = $('.datatable-DeliveryOrder').DataTable(dtOverrideGlobals);

            function postQuick(action, shipmentId, extra) {
                $.post(quickUrl, Object.assign({
                    _token: token,
                    shipment_id: shipmentId,
                    action: action
                }, extra || {}), function (res) {
                    if (res.success) {
                        showAlert('success', res.message, res.message);
                        table.ajax.reload(null, false);
                        refreshStats();
                    } else {
                        showAlert('error', res.message || 'Error', res.message || 'Error');
                    }
                }).fail(function (xhr) {
                    showAlert('error', xhr.responseJSON?.message || 'Error', xhr.responseJSON?.message || 'Error');
                });
            }

            $('.filter-input').on('change', function () {
                table.draw();
                refreshStats();
            });

            refreshStats();

            $(document).on('change', '#select-all-rows', function () {
                $('.shipment-row-select').prop('checked', $(this).is(':checked'));
                updateCodTotal();
            });

            $(document).on('change', '.shipment-row-select', updateCodTotal);

            table.on('draw', function () {
                $('#select-all-rows').prop('checked', false);
                updateCodTotal();
            });

            function confirmThen(fn) {
                Swal.fire({
                    title: confirmMsg,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: @json(__('delivery.messages.confirm_ok')),
                    cancelButtonText: @json(__('delivery.messages.confirm_cancel')),
                }).then(function (result) {
                    if (result.value) fn();
                });
            }

            $(document).on('click', '.btn-quick-delivered', function () {
                const id = $(this).data('id');
                confirmThen(function () { postQuick('delivered', id); });
            });

            $(document).on('click', '.btn-quick-revert', function () {
                const id = $(this).data('id');
                confirmThen(function () { postQuick('revert_handoff', id); });
            });

            function exportRows(ids) {
                const form = $('<form>', { method: 'POST', action: exportUrl });
                form.append($('<input>', { type: 'hidden', name: '_token', value: token }));
                $('.filter-input').each(function () {
                    if ($(this).attr('name') && $(this).val()) {
                        form.append($('<input>', { type: 'hidden', name: $(this).attr('name'), value: $(this).val() }));
                    }
                });
                (ids || []).forEach(function (id) {
                    form.append($('<input>', { type: 'hidden', name: 'ids[]', value: id }));
                });
                $('body').append(form);
                form.submit();
                form.remove();
            }

            $('#btn-export-selected').on('click', function () {
                const ids = selectedIds();
                if (!ids.length) {
                    alert(@json(__('global.datatables.zero_selected')));
                    return;
                }
                exportRows(ids);
            });

            $('#btn-export-all').on('click', function () {
                exportRows([]);
            });
        });
    </script>
@endsection
