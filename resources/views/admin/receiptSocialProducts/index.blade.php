@extends('layouts.admin')
@section('content')
    <div class="form-group">
        <a class="btn btn-dark" href="{{ route('admin.receipt-socials.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>

    @can('receipt_social_product_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.receipt-social-products.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.receiptSocialProduct.title_singular') }}
                </a>
            </div>
        </div>
    @endcan

    @include('admin.receiptSocialProducts.search')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.receiptSocialProduct.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ReceiptSocialProduct">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.receiptSocialProduct.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptSocialProduct.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptSocialProduct.fields.price') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptSocialProduct.fields.commission') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptSocialProduct.fields.photos') }}
                        </th>
                        <th>
                            {{ trans('global.extra.website_setting_id') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


            
            @can('receipt_social_product_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.receipt-social-products.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
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

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.receipt-social-products.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'commission',
                        name: 'commission'
                    },
                    {
                        data: 'photos',
                        name: 'photos',
                        sortable: false,
                        searchable: false
                    },
                    {
                        data: 'website_site_name',
                        name: 'website.site_name'
                    }, 
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-ReceiptSocialProduct').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
