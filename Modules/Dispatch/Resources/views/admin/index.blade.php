@extends('layouts.admin')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="alert alert-info mb-0">
                {{ __('dispatch::messages.queue_hint', ['count' => $queueCount]) }}
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('dispatch::titles.board') }}</span>
            <div>
                <button type="button" class="btn btn-sm btn-warning" id="btn-auto-assign" disabled>
                    {{ __('dispatch::actions.auto_assign') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="dispatch-filters" class="form-row mb-3">
                @if($showPartnerFilter ?? true)
                <div class="col-md-3">
                    <select name="shipping_partner_id" class="form-control filter-input">
                        <option value="">{{ __('cruds.deliveryOrder.fields.shipping_partner') }}</option>
                        @foreach ($shippingPartners as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @elseif(!empty($lockedPartnerId))
                    <input type="hidden" name="shipping_partner_id" class="filter-input" value="{{ $lockedPartnerId }}">
                @endif
                <div class="col-md-3">
                    <input type="text" name="governorate" class="form-control filter-input"
                        placeholder="{{ __('cruds.deliveryOrder.fields.governorate') }}">
                </div>
                <div class="col-md-4">
                    <select id="bulk_courier_id" class="form-control demo-select2">
                        <option value="">{{ __('dispatch::actions.select_courier') }}</option>
                        @foreach ($couriers as $courier)
                            <option value="{{ $courier->id }}">
                                {{ $courier->user?->name }}
                                ({{ __('dispatch::labels.load') }}: {{ $courierLoads[$courier->id] ?? 0 }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-block" id="btn-bulk-assign" disabled>
                        {{ __('dispatch::actions.bulk_assign') }}
                    </button>
                </div>
            </form>

            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Dispatch">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="select-all"></th>
                        <th width="40">#</th>
                        <th>{{ __('cruds.deliveryOrder.fields.order_num') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.shipping_partner') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.governorate') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.remaining_cod') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.status') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.last_status_at') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <form id="bulk-form" method="POST" action="{{ route('admin.dispatch.assign-bulk') }}" class="d-none">
        @csrf
        <input type="hidden" name="courier_id" id="bulk-form-courier">
    </form>
    <form id="auto-form" method="POST" action="{{ route('admin.dispatch.auto-assign') }}" class="d-none">
        @csrf
        <input type="hidden" name="shipping_partner_id" id="auto-form-partner">
    </form>
@endsection

@section('scripts')
    @parent
    <script>
        $(function () {
            let table = $('.datatable-Dispatch').DataTable({
                processing: true,
                serverSide: true,
                retrieve: true,
                select: false,
                columnDefs: [
                    { orderable: false, searchable: false, targets: [0, 1] }
                ],
                ajax: {
                    url: '{{ route('admin.dispatch.index') }}',
                    data: function (d) {
                        $('#dispatch-filters .filter-input').each(function () {
                            d[$(this).attr('name')] = $(this).val();
                        });
                    }
                },
                columns: [
                    { data: 'select', name: 'select', orderable: false, searchable: false },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'order_num', name: 'order_num' },
                    { data: 'partner_name', name: 'shippingPartner.name', orderable: false },
                    { data: 'governorate', name: 'governorate' },
                    { data: 'remaining_cod', name: 'remaining_cod' },
                    { data: 'status', name: 'status', orderable: false },
                    { data: 'last_status_at', name: 'last_status_at' }
                ],
                order: [[2, 'desc']],
                pageLength: 25,
                dom: 'lBfrtip',
                buttons: []
            });

            $('#dispatch-filters .filter-input').on('change keyup', function () {
                table.ajax.reload();
            });

            function selectedIds() {
                return $('.shipment-select:checked').map(function () {
                    return $(this).val();
                }).get();
            }

            function toggleActions() {
                let n = selectedIds().length;
                $('#btn-bulk-assign, #btn-auto-assign').prop('disabled', n === 0);
            }

            $(document).on('change', '.shipment-select, #select-all', function () {
                if ($(this).is('#select-all')) {
                    $('.shipment-select').prop('checked', $(this).is(':checked'));
                }
                toggleActions();
            });

            table.on('draw', function () {
                $('#select-all').prop('checked', false);
                $('.shipment-select').prop('checked', false);
                toggleActions();
            });

            $('#btn-bulk-assign').on('click', function () {
                let courierId = $('#bulk_courier_id').val();
                if (!courierId) {
                    alert('{{ __('dispatch::actions.select_courier_alert') }}');
                    return;
                }
                let ids = selectedIds();
                if (!ids.length) return;

                let form = $('#bulk-form');
                form.find('input[name="shipment_ids[]"]').remove();
                ids.forEach(function (id) {
                    form.append('<input type="hidden" name="shipment_ids[]" value="' + id + '">');
                });
                $('#bulk-form-courier').val(courierId);
                form.submit();
            });

            $('#btn-auto-assign').on('click', function () {
                let ids = selectedIds();
                if (!ids.length) return;

                let form = $('#auto-form');
                form.find('input[name="shipment_ids[]"]').remove();
                ids.forEach(function (id) {
                    form.append('<input type="hidden" name="shipment_ids[]" value="' + id + '">');
                });
                $('#auto-form-partner').val($('select[name=shipping_partner_id]').val() || '');
                form.submit();
            });
        });
    </script>
@endsection
