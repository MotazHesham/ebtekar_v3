@extends('layouts.admin')
@section('content')
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
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-UserAlert">
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
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('user_alert_delete')
  let deleteButtonTrans = '{{ __('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.user-alerts.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.user-alerts.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'alert_text', name: 'alert_text' },
{ data: 'alert_link', name: 'alert_link' },
{ data: 'user', name: 'users.name' },
{ data: 'created_at', name: 'created_at' },
{ data: 'actions', name: '{{ __('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-UserAlert').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection