@extends('layouts.admin')
@section('content')
@can('receipt_price_view_product_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.receipt-price-view-products.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.receiptPriceViewProduct.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.receiptPriceViewProduct.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ReceiptPriceViewProduct">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.price') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.total_cost') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.receipt_price_view') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receiptPriceViewProducts as $key => $receiptPriceViewProduct)
                        <tr data-entry-id="{{ $receiptPriceViewProduct->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $receiptPriceViewProduct->id ?? '' }}
                            </td>
                            <td>
                                {{ $receiptPriceViewProduct->description ?? '' }}
                            </td>
                            <td>
                                {{ $receiptPriceViewProduct->price ?? '' }}
                            </td>
                            <td>
                                {{ $receiptPriceViewProduct->quantity ?? '' }}
                            </td>
                            <td>
                                {{ $receiptPriceViewProduct->total_cost ?? '' }}
                            </td>
                            <td>
                                {{ $receiptPriceViewProduct->receipt_price_view->order_num ?? '' }}
                            </td>
                            <td>
                                @can('receipt_price_view_product_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.receipt-price-view-products.show', $receiptPriceViewProduct->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('receipt_price_view_product_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.receipt-price-view-products.edit', $receiptPriceViewProduct->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('receipt_price_view_product_delete')
                                    <form action="{{ route('admin.receipt-price-view-products.destroy', $receiptPriceViewProduct->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
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



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('receipt_price_view_product_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.receipt-price-view-products.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
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

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-ReceiptPriceViewProduct:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection