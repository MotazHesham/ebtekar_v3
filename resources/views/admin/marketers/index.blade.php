@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Marketers List
            @can('marketer_create')
                <a class="btn btn-success btn-sm float-right" href="{{ route('admin.marketers.create') }}">Add Marketer</a>
            @endcan
            @can('marketer_reports')
                <a class="btn btn-primary btn-sm float-right mr-2" href="{{ route('admin.marketers.reports') }}">Reports</a>
                <a class="btn btn-dark btn-sm float-right mr-2" href="{{ route('admin.marketers.referral-dashboard') }}">Referral Dashboard</a>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-Marketer">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Commission %</th>
                            <th>User</th>
                            <th>Website</th>
                            <th>Status</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($marketers as $marketer)
                            <tr data-entry-id="{{ $marketer->id }}">
                                <td>{{ $marketer->id }}</td>
                                <td>{{ $marketer->name }}</td>
                                <td>{{ $marketer->code }}</td>
                                <td>{{ $marketer->commission_rate }}</td>
                                <td>{{ $marketer->user->name ?? '-' }}</td>
                                <td>{{ $marketer->website->site_name ?? '-' }}</td>
                                <td>
                                    <label class="c-switch c-switch-pill c-switch-success">
                                        <input onchange="update_statuses(this)" value="{{ $marketer->id }}" type="checkbox"
                                            class="c-switch-input" {{ $marketer->is_active ? 'checked' : '' }}>
                                        <span class="c-switch-slider"></span>
                                    </label>
                                </td>
                                <td>
                                    @php
                                        $quickReferral = ($marketer->website && $marketer->website->url)
                                            ? rtrim($marketer->website->url, '/') . '/?ref=' . $marketer->code
                                            : url('/?ref=' . $marketer->code);
                                    @endphp
                                    @can('marketer_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.marketers.show', $marketer->id) }}">View</a>
                                    @endcan
                                    @can('marketer_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.marketers.edit', $marketer->id) }}">Edit</a>
                                    @endcan
                                    <a class="btn btn-xs btn-warning" href="{{ $quickReferral }}" target="_blank">Referral Link</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        function update_statuses(el) {
            var status = el.checked ? 1 : 0;
            $.post('{{ route('admin.marketers.update_statuses') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    showAlert('success', 'Success', '');
                } else {
                    showAlert('danger', 'Something went wrong', '');
                }
            });
        }

        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [0, 'desc']
                ],
                pageLength: 100,
            });
            $('.datatable-Marketer:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            });
        });
    </script>
@endsection
