@extends('layouts.admin')
@section('content')
@can('order_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.orders.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.order.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.order.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Order">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.order.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.order_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.order_num') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.client_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.phone_number') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.phone_number_2') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.shipping_address') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.shipping_country_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.shipping_country_cost') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.shipping_cost_by_seller') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.free_shipping') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.free_shipping_reason') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.printing_times') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.completed') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.calling') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.supplied') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.done_time') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.send_to_delivery_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.send_to_playlist_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.date_of_receiving_order') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.excepected_deliverd_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.playlist_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.payment_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.delivery_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.payment_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.commission_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.deposit_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.deposit_amount') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.total_cost_by_seller') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.total_cost') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.commission') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.extra_commission') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.discount') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.discount_code') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.note') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.cancel_reason') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.delay_reason') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.user') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.shipping_country') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.designer') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.preparer') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.manufacturer') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.shipment') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.delivery_man') }}
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
@can('order_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.orders.massDestroy') }}",
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
    ajax: "{{ route('admin.orders.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'order_type', name: 'order_type' },
{ data: 'order_num', name: 'order_num' },
{ data: 'client_name', name: 'client_name' },
{ data: 'phone_number', name: 'phone_number' },
{ data: 'phone_number_2', name: 'phone_number_2' },
{ data: 'shipping_address', name: 'shipping_address' },
{ data: 'shipping_country_name', name: 'shipping_country_name' },
{ data: 'shipping_country_cost', name: 'shipping_country_cost' },
{ data: 'shipping_cost_by_seller', name: 'shipping_cost_by_seller' },
{ data: 'free_shipping', name: 'free_shipping' },
{ data: 'free_shipping_reason', name: 'free_shipping_reason' },
{ data: 'printing_times', name: 'printing_times' },
{ data: 'completed', name: 'completed' },
{ data: 'calling', name: 'calling' },
{ data: 'supplied', name: 'supplied' },
{ data: 'done_time', name: 'done_time' },
{ data: 'send_to_delivery_date', name: 'send_to_delivery_date' },
{ data: 'send_to_playlist_date', name: 'send_to_playlist_date' },
{ data: 'date_of_receiving_order', name: 'date_of_receiving_order' },
{ data: 'excepected_deliverd_date', name: 'excepected_deliverd_date' },
{ data: 'playlist_status', name: 'playlist_status' },
{ data: 'payment_status', name: 'payment_status' },
{ data: 'delivery_status', name: 'delivery_status' },
{ data: 'payment_type', name: 'payment_type' },
{ data: 'commission_status', name: 'commission_status' },
{ data: 'deposit_type', name: 'deposit_type' },
{ data: 'deposit_amount', name: 'deposit_amount' },
{ data: 'total_cost_by_seller', name: 'total_cost_by_seller' },
{ data: 'total_cost', name: 'total_cost' },
{ data: 'commission', name: 'commission' },
{ data: 'extra_commission', name: 'extra_commission' },
{ data: 'discount', name: 'discount' },
{ data: 'discount_code', name: 'discount_code' },
{ data: 'note', name: 'note' },
{ data: 'cancel_reason', name: 'cancel_reason' },
{ data: 'delay_reason', name: 'delay_reason' },
{ data: 'user_name', name: 'user.name' },
{ data: 'shipping_country_name', name: 'shipping_country.name' },
{ data: 'designer_name', name: 'designer.name' },
{ data: 'preparer_name', name: 'preparer.name' },
{ data: 'manufacturer_name', name: 'manufacturer.name' },
{ data: 'shipment_name', name: 'shipment.name' },
{ data: 'delivery_man_name', name: 'delivery_man.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-Order').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection