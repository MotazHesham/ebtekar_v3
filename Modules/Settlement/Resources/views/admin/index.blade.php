@extends('layouts.admin')

@section('content')
    <div class="row mb-2">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.settlements.create') }}">
                {{ __('settlement::actions.open_new') }}
            </a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form id="settlement-filters" class="form-row">
                <div class="col-md-2">
                    <select name="status" class="form-control filter-input">
                        <option value="">{{ __('settlement::fields.status') }}</option>
                        @foreach (\Modules\Settlement\Enums\SettlementStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ __('settlement::status.' . $status->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="deliver_man_id" class="form-control filter-input demo-select2">
                        <option value="">{{ __('cruds.deliveryOrder.fields.courier') }}</option>
                        @foreach ($couriers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control filter-input">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control filter-input">
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">{{ __('settlement::titles.list') }}</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Settlement">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>{{ __('cruds.deliveryOrder.fields.courier') }}</th>
                        <th>{{ __('settlement::fields.date') }}</th>
                        <th>{{ __('settlement::fields.lines') }}</th>
                        <th>{{ __('settlement::fields.expected') }}</th>
                        <th>{{ __('settlement::fields.collected') }}</th>
                        <th>{{ __('settlement::fields.difference') }}</th>
                        <th>{{ __('settlement::fields.status') }}</th>
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
            let table = $('.datatable-Settlement').DataTable({
                processing: true,
                serverSide: true,
                retrieve: true,
                ajax: {
                    url: '{{ route('admin.settlements.index') }}',
                    data: function (d) {
                        $('#settlement-filters .filter-input').each(function () {
                            d[$(this).attr('name')] = $(this).val();
                        });
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'courier_name', name: 'courier.user.name', orderable: false },
                    { data: 'settlement_date', name: 'settlement_date' },
                    { data: 'lines_count', name: 'lines_count', searchable: false },
                    { data: 'expected_amount', name: 'expected_amount' },
                    { data: 'collected_amount', name: 'collected_amount' },
                    { data: 'difference_amount', name: 'difference_amount' },
                    { data: 'status', name: 'status', orderable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[2, 'desc']]
            });

            $('#settlement-filters .filter-input').on('change', function () {
                table.ajax.reload();
            });
        });
    </script>
@endsection
