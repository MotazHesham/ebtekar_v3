@extends('layouts.admin')
@section('content')
@can('receipt_price_view_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.receipt-price-views.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.receiptPriceView.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.receiptPriceView.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ReceiptPriceView">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.order_num') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.date_of_receiving_order') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.client_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.phone_number') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.total_cost') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.place') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.relate_duration') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.supply_duration') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.payment') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.added_value') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptPriceView.fields.printing_times') }}
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
@can('receipt_price_view_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.receipt-price-views.massDestroy') }}",
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
    ajax: "{{ route('admin.receipt-price-views.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'order_num', name: 'order_num' },
{ data: 'date_of_receiving_order', name: 'date_of_receiving_order' },
{ data: 'client_name', name: 'client_name' },
{ data: 'phone_number', name: 'phone_number' },
{ data: 'total_cost', name: 'total_cost' },
{ data: 'place', name: 'place' },
{ data: 'relate_duration', name: 'relate_duration' },
{ data: 'supply_duration', name: 'supply_duration' },
{ data: 'payment', name: 'payment' },
{ data: 'added_value', name: 'added_value' },
{ data: 'printing_times', name: 'printing_times' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-ReceiptPriceView').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection