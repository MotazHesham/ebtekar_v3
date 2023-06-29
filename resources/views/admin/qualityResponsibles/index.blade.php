@extends('layouts.admin')
@section('content')
@can('quality_responsible_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.quality-responsibles.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.qualityResponsible.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.qualityResponsible.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-QualityResponsible">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.qualityResponsible.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.qualityResponsible.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.qualityResponsible.fields.photo') }}
                        </th>
                        <th>
                            {{ trans('cruds.qualityResponsible.fields.phone_number') }}
                        </th>
                        <th>
                            {{ trans('cruds.qualityResponsible.fields.wts_phone') }}
                        </th>
                        <th>
                            {{ trans('cruds.qualityResponsible.fields.country_code') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($qualityResponsibles as $key => $qualityResponsible)
                        <tr data-entry-id="{{ $qualityResponsible->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $qualityResponsible->id ?? '' }}
                            </td>
                            <td>
                                {{ $qualityResponsible->name ?? '' }}
                            </td>
                            <td>
                                @if($qualityResponsible->photo)
                                    <a href="{{ $qualityResponsible->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $qualityResponsible->photo->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $qualityResponsible->phone_number ?? '' }}
                            </td>
                            <td>
                                {{ $qualityResponsible->wts_phone ?? '' }}
                            </td>
                            <td>
                                {{ $qualityResponsible->country_code ?? '' }}
                            </td>
                            <td>
                                @can('quality_responsible_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.quality-responsibles.show', $qualityResponsible->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('quality_responsible_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.quality-responsibles.edit', $qualityResponsible->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('quality_responsible_delete')
                                <?php $route = route('admin.quality-responsibles.destroy', $qualityResponsible->id); ?>
                                <a class="btn btn-xs btn-danger" href="#" onclick="deleteConfirmation('{{$route}}')">
                                    {{ trans('global.delete') }}  
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
@can('quality_responsible_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.quality-responsibles.massDestroy') }}",
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
  let table = $('.datatable-QualityResponsible:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection