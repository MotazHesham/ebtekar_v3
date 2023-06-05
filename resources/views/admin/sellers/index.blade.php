@extends('layouts.admin')
@section('content')
@can('seller_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.sellers.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.seller.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.seller.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Seller">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.user') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.seller_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.discount') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.discount_code') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.order_out_website') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.order_in_website') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.qualification') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.social_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.social_link') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.seller_code') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.identity_back') }}
                    </th>
                    <th>
                        {{ trans('cruds.seller.fields.identity_front') }}
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
@can('seller_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.sellers.massDestroy') }}",
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
    ajax: "{{ route('admin.sellers.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'user_name', name: 'user.name' },
{ data: 'seller_type', name: 'seller_type' },
{ data: 'discount', name: 'discount' },
{ data: 'discount_code', name: 'discount_code' },
{ data: 'order_out_website', name: 'order_out_website' },
{ data: 'order_in_website', name: 'order_in_website' },
{ data: 'qualification', name: 'qualification' },
{ data: 'social_name', name: 'social_name' },
{ data: 'social_link', name: 'social_link' },
{ data: 'seller_code', name: 'seller_code' },
{ data: 'identity_back', name: 'identity_back', sortable: false, searchable: false },
{ data: 'identity_front', name: 'identity_front', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-Seller').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection