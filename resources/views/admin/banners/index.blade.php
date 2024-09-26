@extends('layouts.admin')
@section('content')
    @can('banner_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.banners.create') }}">
                    {{ __('global.add') }} {{ __('cruds.banner.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ __('cruds.banner.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Banner">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ __('cruds.banner.fields.id') }}
                            </th>
                            <th>
                                {{ __('cruds.banner.fields.photo') }}
                            </th>
                            <th>
                                {{ __('cruds.banner.fields.url') }}
                            </th>
                            <th>
                                {{ __('cruds.banner.fields.position') }}
                            </th>
                            <th>
                                {{ __('global.extra.website_setting_id') }}
                            </th>
                            <th>
                                {{ __('cruds.banner.fields.published') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $key => $banner)
                            <tr data-entry-id="{{ $banner->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $banner->id ?? '' }}
                                </td>
                                <td>
                                    @if ($banner->photo)
                                        <a href="{{ $banner->photo->getUrl() }}" target="_blank"
                                            style="display: inline-block">
                                            <img src="{{ $banner->photo->getUrl('thumb') }}">
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    {{ $banner->url ?? '' }}
                                </td>
                                <td>
                                    {{ $banner->website->site_name ?? '' }}
                                </td>
                                <td>
                                    {{ App\Models\Banner::POSITION_SELECT[$banner->position] ?? '' }}
                                </td>
                                <td>
                                    <label class="c-switch c-switch-pill c-switch-success">
                                        <input onchange="update_statuses(this,'published')" value="{{ $banner->id }}"
                                            type="checkbox" class="c-switch-input"
                                            {{ $banner->published ? 'checked' : null }}>
                                        <span class="c-switch-slider"></span>
                                    </label>
                                </td>
                                <td>
                                    @can('banner_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.banners.show', $banner->id) }}">
                                            {{ __('global.view') }}
                                        </a>
                                    @endcan

                                    @can('banner_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.banners.edit', $banner->id) }}">
                                            {{ __('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('banner_delete')
                                        <?php $route = route('admin.banners.destroy', $banner->id); ?>
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
        function update_statuses(el,type){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.banners.update_statuses') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status, type:type}, function(data){
                if(data == 1){
                    showAlert('success', 'Success', '');
                }else{
                    showAlert('danger', 'Something went wrong', '');
                }
            });
        }
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('banner_delete')
                let deleteButtonTrans = '{{ __('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.banners.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ __('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ __('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            });
            let table = $('.datatable-Banner:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
