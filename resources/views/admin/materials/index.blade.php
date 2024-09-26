@extends('layouts.admin')
@section('content')
@can('material_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.materials.create') }}">
                {{ __('global.add') }} {{ __('cruds.material.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ __('cruds.material.title_singular') }} {{ __('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Material">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.material.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.material.fields.name') }}
                        </th>
                        <th>
                            {{ __('cruds.material.fields.description') }}
                        </th>
                        <th>
                            {{ __('cruds.material.fields.remaining') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $key => $material)
                        <tr data-entry-id="{{ $material->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $material->id ?? '' }}
                            </td>
                            <td>
                                {{ $material->name ?? '' }}
                            </td>
                            <td>
                                {{ $material->description ?? '' }}
                            </td>
                            <td>
                                {{ $material->remaining ?? '' }}
                            </td>
                            <td>
                                @can('material_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.materials.show', $material->id) }}">
                                        {{ __('global.view') }}
                                    </a>
                                @endcan

                                @can('material_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.materials.edit', $material->id) }}">
                                        {{ __('global.edit') }}
                                    </a>
                                @endcan

                                @can('material_delete')
                                    <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('{{ __('global.areYouSure') }}');" style="display: inline-block;">
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
@can('material_delete')
  let deleteButtonTrans = '{{ __('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.materials.massDestroy') }}",
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
  let table = $('.datatable-Material:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection