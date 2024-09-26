@extends('layouts.admin')
@section('content')
@can('social_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.socials.create') }}">
                {{ __('global.add') }} {{ __('cruds.social.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ __('cruds.social.title_singular') }} {{ __('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Social">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.social.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.social.fields.name') }}
                        </th>
                        <th>
                            {{ __('cruds.social.fields.photo') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($socials as $key => $social)
                        <tr data-entry-id="{{ $social->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $social->id ?? '' }}
                            </td>
                            <td>
                                {{ $social->name ?? '' }}
                            </td>
                            <td>
                                @if($social->photo)
                                    <a href="{{ $social->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $social->photo->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                @can('social_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.socials.show', $social->id) }}">
                                        {{ __('global.view') }}
                                    </a>
                                @endcan

                                @can('social_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.socials.edit', $social->id) }}">
                                        {{ __('global.edit') }}
                                    </a>
                                @endcan

                                @can('social_delete')
                                    <?php $route = route('admin.socials.destroy', $social->id); ?>
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
@can('social_delete')
  let deleteButtonTrans = '{{ __('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.socials.massDestroy') }}",
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
  let table = $('.datatable-Social:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection