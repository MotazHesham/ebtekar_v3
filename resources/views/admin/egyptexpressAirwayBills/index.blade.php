@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('cruds.egyptexpressAirwayBill.title') }} {{ __('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-EgyptExpressAirwayBill">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.id') }}
                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.shipper_reference') }}
                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.order_num') }}
                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.airway_bill_number') }}
                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.tracking_number') }}
                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.receiver_name') }}
                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.receiver_phone') }}
                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.receiver_city') }}
                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.destination') }}
                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.is_successful') }}
                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.model_type') }}
                    </th>
                    <th>
                        {{ __('cruds.egyptexpressAirwayBill.fields.created_at') }}
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
  
  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.egyptexpress-airway-bills.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'shipper_reference', name: 'shipper_reference' },
{ data: 'order_num', name: 'order_num' },
{ data: 'airway_bill_number', name: 'airway_bill_number' },
{ data: 'tracking_number', name: 'tracking_number' },
{ data: 'receiver_name', name: 'receiver_name' },
{ data: 'receiver_phone', name: 'receiver_phone' },
{ data: 'receiver_city', name: 'receiver_city' },
{ data: 'destination', name: 'destination' },
{ data: 'is_successful', name: 'is_successful' },
{ data: 'model_type', name: 'model_type' },
{ data: 'created_at', name: 'created_at' },
{ data: 'actions', name: '{{ __('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-EgyptExpressAirwayBill').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection
