@can('user_alert_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.user-alerts.create') }}">
                {{ __('global.add') }} {{ __('cruds.userAlert.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ __('cruds.userAlert.title_singular') }} {{ __('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-userUserAlerts">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.userAlert.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.userAlert.fields.alert_text') }}
                        </th>
                        <th>
                            {{ __('cruds.userAlert.fields.alert_link') }}
                        </th>
                        <th>
                            {{ __('cruds.userAlert.fields.user') }}
                        </th>
                        <th>
                            {{ __('cruds.userAlert.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userAlerts as $key => $userAlert)
                        <tr data-entry-id="{{ $userAlert->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $userAlert->id ?? '' }}
                            </td>
                            <td>
                                {{ $userAlert->alert_text ?? '' }}
                            </td>
                            <td>
                                {{ $userAlert->alert_link ?? '' }}
                            </td>
                            <td>
                                @foreach($userAlert->users as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $userAlert->created_at ?? '' }}
                            </td>
                            <td>
                                @can('user_alert_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.user-alerts.show', $userAlert->id) }}">
                                        {{ __('global.view') }}
                                    </a>
                                @endcan


                                @can('user_alert_delete')
                                    <form action="{{ route('admin.user-alerts.destroy', $userAlert->id) }}" method="POST" onsubmit="return confirm('{{ __('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ __('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('user_alert_delete')
  let deleteButtonTrans = '{{ __('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.user-alerts.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ __('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ __('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-userUserAlerts:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection