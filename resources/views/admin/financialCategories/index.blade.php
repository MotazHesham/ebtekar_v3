@extends('layouts.admin')
@section('content')
@can('financial_category_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.financial-categories.create') }}">
                {{ __('global.add') }} {{ __('cruds.financialCategory.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ __('cruds.financialCategory.title_singular') }} {{ __('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-FinancialCategory">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.financialCategory.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.financialCategory.fields.name') }}
                        </th>
                        <th>
                            {{ __('cruds.financialCategory.fields.type') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($financialCategories as $key => $financialCategory)
                        <tr data-entry-id="{{ $financialCategory->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $financialCategory->id ?? '' }}
                            </td>
                            <td>
                                {{ $financialCategory->name ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\FinancialCategory::TYPE_RADIO[$financialCategory->type] ?? '' }}
                            </td>
                            <td>
                                @can('financial_category_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.financial-categories.show', $financialCategory->id) }}">
                                        {{ __('global.view') }}
                                    </a>
                                @endcan

                                @can('financial_category_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.financial-categories.edit', $financialCategory->id) }}">
                                        {{ __('global.edit') }}
                                    </a>
                                @endcan

                                @can('financial_category_delete')
                                    <form action="{{ route('admin.financial-categories.destroy', $financialCategory->id) }}" method="POST" onsubmit="return confirm('{{ __('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ __('global.delete') }}">
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
@can('financial_category_delete')
  let deleteButtonTrans = '{{ __('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.financial-categories.massDestroy') }}",
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
  let table = $('.datatable-FinancialCategory:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection