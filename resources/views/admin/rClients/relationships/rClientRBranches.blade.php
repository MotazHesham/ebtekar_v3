@can('r_branch_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.r-branches.create') }}">
                {{ __('global.add') }} {{ __('cruds.rBranch.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ __('cruds.rBranch.title_singular') }} {{ __('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-rClientRBranches">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.rBranch.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.rBranch.fields.name') }}
                        </th>
                        <th>
                            {{ __('cruds.rBranch.fields.phone_number') }}
                        </th>
                        <th>
                            {{ __('cruds.rBranch.fields.payment_type') }}
                        </th>
                        <th>
                            {{ __('cruds.rBranch.fields.remaining') }}
                        </th>
                        <th>
                            {{ __('cruds.rBranch.fields.r_client') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rBranches as $key => $rBranch)
                        <tr data-entry-id="{{ $rBranch->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $rBranch->id ?? '' }}
                            </td>
                            <td>
                                {{ $rBranch->name ?? '' }}
                            </td>
                            <td>
                                {{ $rBranch->phone_number ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\RBranch::PAYMENT_TYPE_SELECT[$rBranch->payment_type] ?? '' }}
                            </td>
                            <td>
                                {{ $rBranch->remaining ?? '' }}
                            </td>
                            <td>
                                {{ $rBranch->r_client->name ?? '' }}
                            </td>
                            <td>
                                @can('r_branch_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.r-branches.show', $rBranch->id) }}">
                                        {{ __('global.view') }}
                                    </a>
                                @endcan

                                @can('r_branch_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.r-branches.edit', $rBranch->id) }}">
                                        {{ __('global.edit') }}
                                    </a>
                                @endcan

                                @can('r_branch_delete')
                                    <form action="{{ route('admin.r-branches.destroy', $rBranch->id) }}" method="POST" onsubmit="return confirm('{{ __('global.areYouSure') }}');" style="display: inline-block;">
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

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('r_branch_delete')
  let deleteButtonTrans = '{{ __('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.r-branches.massDestroy') }}",
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
  let table = $('.datatable-rClientRBranches:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection