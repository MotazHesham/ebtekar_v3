@can('receipt_outgoing_product_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.receipt-outgoing-products.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.receiptOutgoingProduct.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.receiptOutgoingProduct.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-receiptOutgoingReceiptOutgoingProducts">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.price') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.total_cost') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.receipt_outgoing') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receiptOutgoingProducts as $key => $receiptOutgoingProduct)
                        <tr data-entry-id="{{ $receiptOutgoingProduct->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $receiptOutgoingProduct->id ?? '' }}
                            </td>
                            <td>
                                {{ $receiptOutgoingProduct->description ?? '' }}
                            </td>
                            <td>
                                {{ $receiptOutgoingProduct->price ?? '' }}
                            </td>
                            <td>
                                {{ $receiptOutgoingProduct->quantity ?? '' }}
                            </td>
                            <td>
                                {{ $receiptOutgoingProduct->total_cost ?? '' }}
                            </td>
                            <td>
                                {{ $receiptOutgoingProduct->receipt_outgoing->order_num ?? '' }}
                            </td>
                            <td>
                                @can('receipt_outgoing_product_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.receipt-outgoing-products.show', $receiptOutgoingProduct->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('receipt_outgoing_product_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.receipt-outgoing-products.edit', $receiptOutgoingProduct->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('receipt_outgoing_product_delete')
                                    <form action="{{ route('admin.receipt-outgoing-products.destroy', $receiptOutgoingProduct->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('receipt_outgoing_product_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.receipt-outgoing-products.massDestroy') }}",
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
  let table = $('.datatable-receiptOutgoingReceiptOutgoingProducts:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection