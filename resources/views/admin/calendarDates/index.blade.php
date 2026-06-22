@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('cruds.calendarDate.title') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-CalendarDate">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ __('cruds.calendarDate.fields.id') }}</th>
                        <th>{{ __('cruds.calendarDate.fields.user') }}</th>
                        <th>{{ __('cruds.calendarDate.fields.user_phone') }}</th>
                        <th>{{ __('cruds.calendarDate.fields.type') }}</th>
                        <th>{{ __('cruds.calendarDate.fields.description') }}</th>
                        <th>{{ __('cruds.calendarDate.fields.event_date') }}</th>
                        <th>{{ __('cruds.calendarDate.fields.reminder_days_before') }}</th>
                        <th>{{ __('cruds.calendarDate.fields.reminder_source') }}</th>
                        <th>{{ __('cruds.calendarDate.fields.created_at') }}</th>
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
            @can('calendar_date_delete')
                let deleteButtonTrans = '{{ __('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.calendar-dates.massDestroy') }}",
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
                ajax: "{{ route('admin.calendar-dates.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'user_name',
                        name: 'user.name'
                    },
                    {
                        data: 'user_phone',
                        name: 'user.phone_number'
                    },
                    {
                        data: 'type_label',
                        name: 'type'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'event_date',
                        name: 'event_date'
                    },
                    {
                        data: 'reminder_days',
                        name: 'reminder_days_before'
                    },
                    {
                        data: 'uses_default_reminder',
                        name: 'uses_default_reminder',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
            $('.datatable-CalendarDate').DataTable(dtOverrideGlobals);
        });
    </script>
@endsection
