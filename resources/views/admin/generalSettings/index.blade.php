@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.generalSetting.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-GeneralSetting">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.generalSetting.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.generalSetting.fields.logo') }}
                        </th>
                        <th>
                            {{ trans('cruds.generalSetting.fields.site_name') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($generalSettings as $key => $generalSetting)
                        <tr data-entry-id="{{ $generalSetting->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $generalSetting->id ?? '' }}
                            </td>
                            <td>
                                @if($generalSetting->logo)
                                    <a href="{{ $generalSetting->logo->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $generalSetting->logo->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $generalSetting->site_name ?? '' }}
                            </td>
                            <td>
                                @can('general_setting_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.general-settings.show', $generalSetting->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('general_setting_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.general-settings.edit', $generalSetting->id) }}">
                                        {{ trans('global.edit') }}
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
  
  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  });
  let table = $('.datatable-GeneralSetting:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection