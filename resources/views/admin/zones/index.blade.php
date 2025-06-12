@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.zone.title_singular') }} {{ trans('global.list') }}
        @can('country_create')
            <a class="btn btn-success float-right" href="{{ route('admin.zones.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.zone.title_singular') }}
            </a>
        @endcan
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Zone">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.zone.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.zone.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.zone.fields.delivery_cost') }}
                        </th>
                        <th>
                            {{ trans('cruds.zone.fields.countries') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($zones as $key => $zone)
                        <tr data-entry-id="{{ $zone->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $zone->id ?? '' }}
                            </td>
                            <td>
                                {{ $zone->name ?? '' }}
                            </td>
                            <td>
                                {{ $zone->delivery_cost ?? '' }}
                            </td>
                            <td>
                                @foreach($zone->countries as $key => $country)
                                    <span class="badge badge-info">{{ $country->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @can('country_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.zones.edit', $zone->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('country_delete')
                                    <form action="{{ route('admin.zones.destroy', $zone->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
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

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 100,
        });
        let table = $('.datatable-Zone:not(.ajaxTable)').DataTable({ buttons: dtButtons })
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })
</script>
@endsection 