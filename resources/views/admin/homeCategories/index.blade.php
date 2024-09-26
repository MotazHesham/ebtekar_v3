@extends('layouts.admin')
@section('content')
    @can('home_category_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.home-categories.create') }}">
                    {{ __('global.add') }} {{ __('cruds.homeCategory.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ __('cruds.homeCategory.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-HomeCategory">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ __('cruds.homeCategory.fields.id') }}
                            </th>
                            <th>
                                {{ __('cruds.homeCategory.fields.category') }}
                            </th>
                        <th>
                            {{ __('global.extra.website_setting_id') }}
                        </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($homeCategories as $key => $homeCategory)
                            <tr data-entry-id="{{ $homeCategory->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $homeCategory->id ?? '' }}
                                </td>
                                <td>
                                    {{ $homeCategory->category->name ?? '' }}
                                </td> 
                                <td>
                                    {{ $homeCategory->website->site_name ?? '' }}
                                </td> 
                                <td>
                                    @can('home_category_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.home-categories.show', $homeCategory->id) }}">
                                            {{ __('global.view') }}
                                        </a>
                                    @endcan

                                    @can('home_category_edit')
                                        <a class="btn btn-xs btn-info"
                                            href="{{ route('admin.home-categories.edit', $homeCategory->id) }}">
                                            {{ __('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('home_category_delete')
                                        <?php $route = route('admin.home-categories.destroy', $homeCategory->id); ?>
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
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('home_category_delete')
                let deleteButtonTrans = '{{ __('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.home-categories.massDestroy') }}",
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
                pageLength: 10,
            });
            let table = $('.datatable-HomeCategory:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
