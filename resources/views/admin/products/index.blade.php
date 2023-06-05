@extends('layouts.admin')
@section('content')
@can('product_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.products.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.product.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.product.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Product">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.product.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.added_by') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.unit_price') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.purchase_price') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.slug') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.attributes') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.choice_options') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.colors') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.tags') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.video_provider') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.video_link') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.photos') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.pdf') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.discount_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.discount') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.meta_title') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.meta_description') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.flash_deal') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.published') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.featured') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.todays_deal') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.current_stock') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.user') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.category') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.sub_category') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.sub_sub_category') }}
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
@can('product_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.products.massDestroy') }}",
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
    ajax: "{{ route('admin.products.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name', name: 'name' },
{ data: 'added_by', name: 'added_by' },
{ data: 'unit_price', name: 'unit_price' },
{ data: 'purchase_price', name: 'purchase_price' },
{ data: 'slug', name: 'slug' },
{ data: 'attributes', name: 'attributes' },
{ data: 'choice_options', name: 'choice_options' },
{ data: 'colors', name: 'colors' },
{ data: 'tags', name: 'tags' },
{ data: 'video_provider', name: 'video_provider' },
{ data: 'video_link', name: 'video_link' },
{ data: 'photos', name: 'photos', sortable: false, searchable: false },
{ data: 'pdf', name: 'pdf', sortable: false, searchable: false },
{ data: 'discount_type', name: 'discount_type' },
{ data: 'discount', name: 'discount' },
{ data: 'meta_title', name: 'meta_title' },
{ data: 'meta_description', name: 'meta_description' },
{ data: 'flash_deal', name: 'flash_deal' },
{ data: 'published', name: 'published' },
{ data: 'featured', name: 'featured' },
{ data: 'todays_deal', name: 'todays_deal' },
{ data: 'current_stock', name: 'current_stock' },
{ data: 'user_name', name: 'user.name' },
{ data: 'category_name', name: 'category.name' },
{ data: 'sub_category_name', name: 'sub_category.name' },
{ data: 'sub_sub_category_name', name: 'sub_sub_category.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-Product').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection