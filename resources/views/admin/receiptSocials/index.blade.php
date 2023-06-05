@extends('layouts.admin')
@section('content')
@can('receipt_social_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.receipt-socials.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.receiptSocial.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.receiptSocial.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ReceiptSocial">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.order_num') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.client_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.client_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.phone_number') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.phone_number_2') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.deposit') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.discount') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.commission') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.extra_commission') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.total_cost') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.done') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.quickly') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.confirm') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.returned') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.supplied') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.printing_times') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.shipping_country_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.shipping_country_cost') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.shipping_address') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.date_of_receiving_order') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.deliver_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.send_to_delivery_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.send_to_playlist_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.done_time') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.cancel_reason') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.delay_reason') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.delivery_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.note') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.payment_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.playlist_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.staff') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.designer') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.preparer') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.manufacturer') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.shipmenter') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.delivery_man') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.shipping_country') }}
                    </th>
                    <th>
                        {{ trans('cruds.receiptSocial.fields.socials') }}
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
@can('receipt_social_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.receipt-socials.massDestroy') }}",
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
    ajax: "{{ route('admin.receipt-socials.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'order_num', name: 'order_num' },
{ data: 'client_name', name: 'client_name' },
{ data: 'client_type', name: 'client_type' },
{ data: 'phone_number', name: 'phone_number' },
{ data: 'phone_number_2', name: 'phone_number_2' },
{ data: 'deposit', name: 'deposit' },
{ data: 'discount', name: 'discount' },
{ data: 'commission', name: 'commission' },
{ data: 'extra_commission', name: 'extra_commission' },
{ data: 'total_cost', name: 'total_cost' },
{ data: 'done', name: 'done' },
{ data: 'quickly', name: 'quickly' },
{ data: 'confirm', name: 'confirm' },
{ data: 'returned', name: 'returned' },
{ data: 'supplied', name: 'supplied' },
{ data: 'printing_times', name: 'printing_times' },
{ data: 'shipping_country_name', name: 'shipping_country_name' },
{ data: 'shipping_country_cost', name: 'shipping_country_cost' },
{ data: 'shipping_address', name: 'shipping_address' },
{ data: 'date_of_receiving_order', name: 'date_of_receiving_order' },
{ data: 'deliver_date', name: 'deliver_date' },
{ data: 'send_to_delivery_date', name: 'send_to_delivery_date' },
{ data: 'send_to_playlist_date', name: 'send_to_playlist_date' },
{ data: 'done_time', name: 'done_time' },
{ data: 'cancel_reason', name: 'cancel_reason' },
{ data: 'delay_reason', name: 'delay_reason' },
{ data: 'delivery_status', name: 'delivery_status' },
{ data: 'note', name: 'note' },
{ data: 'payment_status', name: 'payment_status' },
{ data: 'playlist_status', name: 'playlist_status' },
{ data: 'staff_name', name: 'staff.name' },
{ data: 'designer_name', name: 'designer.name' },
{ data: 'preparer_name', name: 'preparer.name' },
{ data: 'manufacturer_name', name: 'manufacturer.name' },
{ data: 'shipmenter_name', name: 'shipmenter.name' },
{ data: 'delivery_man_name', name: 'delivery_man.name' },
{ data: 'shipping_country_name', name: 'shipping_country.name' },
{ data: 'socials', name: 'socials.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-ReceiptSocial').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection