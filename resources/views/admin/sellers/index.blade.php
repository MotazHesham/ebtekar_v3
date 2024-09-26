@extends('layouts.admin')
@section('content')
    @can('seller_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.sellers.create') }}">
                    {{ __('global.add') }} {{ __('cruds.seller.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ __('cruds.seller.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Seller">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.seller.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.user.fields.name') }}
                        </th>
                        <th>
                            {{ __('cruds.user.fields.email') }}
                        </th>
                        <th>
                            {{ __('cruds.user.fields.phone_number') }}
                        </th>
                        <th>
                            {{ __('cruds.user.fields.address') }}
                        </th>
                        <th>
                            {{ __('cruds.seller.fields.seller_type') }}
                        </th>
                        <th>
                            {{ __('cruds.seller.fields.discount') }}
                        </th>
                        <th>
                            {{ __('cruds.seller.fields.discount_code') }}
                        </th>
                        <th>
                            {{ __('cruds.seller.fields.qualification') }}
                        </th>
                        <th>
                            {{ __('cruds.seller.fields.social_name') }}
                        </th>
                        <th>
                            {{ __('cruds.seller.fields.social_link') }}
                        </th>
                        <th>
                            {{ __('cruds.seller.fields.seller_code') }}
                        </th>
                        <th>
                            {{ __('cruds.user.fields.approved') }}
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
        function update_statuses(el,type){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.users.update_statuses') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status, type:type}, function(data){
                if(data == 1){
                    showAlert('success', 'Success', '');
                }else{
                    showAlert('danger', 'Something went wrong', '');
                }
            });
        }
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('seller_delete')
                let deleteButtonTrans = '{{ __('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.sellers.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
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

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.sellers.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'user_name',
                        name: 'user.name'
                    },
                    {
                        data: 'user_email',
                        name: 'user.email'
                    },
                    {
                        data: 'user_phone_number',
                        name: 'user.phone_number'
                    },
                    {
                        data: 'user_address',
                        name: 'user.address'
                    },
                    {
                        data: 'seller_type',
                        name: 'seller_type'
                    },
                    {
                        data: 'discount',
                        name: 'discount'
                    },
                    {
                        data: 'discount_code',
                        name: 'discount_code'
                    },
                    {
                        data: 'qualification',
                        name: 'qualification'
                    },
                    {
                        data: 'social_name',
                        name: 'social_name'
                    },
                    {
                        data: 'social_link',
                        name: 'social_link'
                    },
                    {
                        data: 'seller_code',
                        name: 'seller_code'
                    },
                    {
                        data: 'user_approved',
                        name: 'user.approved'
                    }, 
                    {
                        data: 'actions',
                        name: '{{ __('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            };
            let table = $('.datatable-Seller').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
