@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('cruds.subscribe.title_singular') }} {{ __('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Subscribe">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ __('cruds.subscribe.fields.id') }}
                    </th>
                    <th>
                        {{ __('cruds.subscribe.fields.name') }}
                    </th>
                    <th>
                        {{ __('cruds.subscribe.fields.email') }}
                    </th>
                    <th>
                        {{ __('cruds.subscribe.fields.from_website') }}
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
@can('subscribe_delete')
  let deleteButtonTrans = '{{ __('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.subscribes.massDestroy') }}",
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
    ajax: "{{ route('admin.subscribes.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name', name: 'name' },
{ data: 'email', name: 'email' },
{ data: 'from_website', name: 'from_website' },
{ data: 'actions', name: '{{ __('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-Subscribe').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection