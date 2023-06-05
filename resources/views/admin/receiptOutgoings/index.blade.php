@extends('layouts.admin')
@section('content')
@can('receipt_outgoing_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.receipt-outgoings.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.receiptOutgoing.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.receiptOutgoing.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ReceiptOutgoing">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.receiptOutgoing.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptOutgoing.fields.order_num') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptOutgoing.fields.date_of_receiving_order') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptOutgoing.fields.client_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptOutgoing.fields.phone_number') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptOutgoing.fields.total_cost') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptOutgoing.fields.note') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptOutgoing.fields.done') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptOutgoing.fields.printing_times') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptOutgoing.fields.staff') }}
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
@can('receipt_outgoing_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.receipt-outgoings.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
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
    ajax: "{{ route('admin.receipt-outgoings.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'order_num', name: 'order_num' },
{ data: 'date_of_receiving_order', name: 'date_of_receiving_order' },
{ data: 'client_name', name: 'client_name' },
{ data: 'phone_number', name: 'phone_number' },
{ data: 'total_cost', name: 'total_cost' },
{ data: 'note', name: 'note' },
{ data: 'done', name: 'done' },
{ data: 'printing_times', name: 'printing_times' },
{ data: 'staff_name', name: 'staff.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-ReceiptOutgoing').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection