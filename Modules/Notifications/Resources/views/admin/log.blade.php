@extends('layouts.admin')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form id="notification-filters" class="form-row">
                <div class="col-md-3">
                    <select name="channel" class="form-control filter-input">
                        <option value="">{{ __('notifications::fields.channel') }}</option>
                        <option value="push">{{ __('notifications::channels.push') }}</option>
                        <option value="database">{{ __('notifications::channels.database') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="event_type" class="form-control filter-input"
                        placeholder="{{ __('notifications::fields.event_type') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control filter-input">
                        <option value="">{{ __('notifications::fields.status') }}</option>
                        <option value="sent">{{ __('notifications::delivery_status.sent') }}</option>
                        <option value="failed">{{ __('notifications::delivery_status.failed') }}</option>
                        <option value="skipped">{{ __('notifications::delivery_status.skipped') }}</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">{{ __('notifications::titles.log') }}</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-NotificationLog">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('notifications::fields.channel') }}</th>
                        <th>{{ __('notifications::fields.event_type') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.order_num') }}</th>
                        <th>{{ __('notifications::fields.recipient') }}</th>
                        <th>{{ __('notifications::fields.title') }}</th>
                        <th>{{ __('notifications::fields.status') }}</th>
                        <th>{{ __('global.created_at') }}</th>
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
            let table = $('.datatable-NotificationLog').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.notification-deliveries.index') }}',
                    data: function (d) {
                        $('#notification-filters .filter-input').each(function () {
                            d[$(this).attr('name')] = $(this).val();
                        });
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'channel', name: 'channel' },
                    { data: 'event_type', name: 'event_type' },
                    { data: 'order_num', name: 'shipment.order_num', orderable: false },
                    { data: 'user_name', name: 'user.name', orderable: false },
                    { data: 'title', name: 'title' },
                    { data: 'status', name: 'status', orderable: false },
                    { data: 'created_at', name: 'created_at' }
                ],
                order: [[0, 'desc']]
            });

            $('#notification-filters .filter-input').on('change keyup', function () {
                table.ajax.reload();
            });
        });
    </script>
@endsection
