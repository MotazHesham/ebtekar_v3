@extends('layouts.admin')
@section('content')

@can('website_setting_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.website-settings.create') }}">
                {{ __('global.add') }} {{ __('cruds.websiteSetting.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ __('cruds.websiteSetting.title_singular') }} {{ __('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-GeneralSetting">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.websiteSetting.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.websiteSetting.fields.logo') }}
                        </th>
                        <th>
                            {{ __('cruds.websiteSetting.fields.site_name') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($websiteSettings as $key => $websiteSetting)
                        <tr data-entry-id="{{ $websiteSetting->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $websiteSetting->id ?? '' }}
                            </td>
                            <td>
                                @if($websiteSetting->logo)
                                    <a href="{{ $websiteSetting->logo->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $websiteSetting->logo->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $websiteSetting->site_name ?? '' }}
                            </td>
                            <td>
                                @can('website_setting_show')
                                    {{-- <a class="btn btn-xs btn-primary" href="{{ route('admin.website-settings.show', $websiteSetting->id) }}">
                                        {{ __('global.view') }}
                                    </a> --}}
                                @endcan

                                @can('website_setting_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.website-settings.edit', $websiteSetting->id) }}">
                                        {{ __('global.edit') }}
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