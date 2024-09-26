@extends('layouts.admin')
@section('content')
@can('faq_category_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.faq-categories.create') }}">
                {{ __('global.add') }} {{ __('cruds.faqCategory.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ __('cruds.faqCategory.title_singular') }} {{ __('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-FaqCategory">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.faqCategory.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.faqCategory.fields.category') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faqCategories as $key => $faqCategory)
                        <tr data-entry-id="{{ $faqCategory->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $faqCategory->id ?? '' }}
                            </td>
                            <td>
                                {{ $faqCategory->category ?? '' }}
                            </td>
                            <td>
                                @can('faq_category_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.faq-categories.show', $faqCategory->id) }}">
                                        {{ __('global.view') }}
                                    </a>
                                @endcan

                                @can('faq_category_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.faq-categories.edit', $faqCategory->id) }}">
                                        {{ __('global.edit') }}
                                    </a>
                                @endcan

                                @can('faq_category_delete') 
                                    <?php $route = route('admin.faq-categories.destroy', $faqCategory->id); ?>
                                    <a class="btn btn-xs btn-danger" href="#" onclick="deleteConfirmation('{{$route}}')">
                                        {{ __('global.delete') }}  
                                    </a>  
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
@can('faq_category_delete')
  let deleteButtonTrans = '{{ __('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.faq-categories.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
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

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-FaqCategory:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection