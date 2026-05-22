@extends('layouts.admin')
@section('content')
    @can('shipping_partner_create')
        <div class="row mb-2">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.shipping-partners.create') }}">
                    {{ __('global.add') }} {{ __('cruds.shippingPartner.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">{{ __('cruds.shippingPartner.title') }} {{ __('global.list') }}</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-ShippingPartner">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>ID</th>
                        <th>{{ __('cruds.shippingPartner.fields.name') }}</th>
                        <th>{{ __('cruds.shippingPartner.fields.code') }}</th>
                        <th>{{ __('cruds.user.fields.email') }}</th>
                        <th>{{ __('cruds.shippingPartner.fields.phone') }}</th>
                        <th>{{ __('cruds.shippingPartner.fields.management_type') }}</th>
                        <th>{{ __('cruds.shippingPartner.fields.is_active') }}</th>
                        <th></th>
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
            @can('shipping_partner_delete')
                dtButtons.push({
                    text: '{{ __('global.datatables.delete') }}',
                    url: "{{ route('admin.shipping-partners.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({ selected: true }).data(), function(entry) {
                            return entry.id
                        });
                        if (!ids.length) return alert('{{ __('global.datatables.zero_selected') }}');
                        if (confirm('{{ __('global.areYouSure') }}')) {
                            $.ajax({
                                headers: { 'x-csrf-token': _token },
                                method: 'POST',
                                url: config.url,
                                data: { ids: ids, _method: 'DELETE' }
                            }).done(function() { location.reload() })
                        }
                    }
                })
            @endcan

            $('.datatable-ShippingPartner').DataTable({
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                ajax: "{{ route('admin.shipping-partners.index') }}",
                columns: [
                    { data: 'placeholder', orderable: false, searchable: false },
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'code', name: 'code' },
                    { data: 'user_email', name: 'user.email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'management_type_label', name: 'management_type', orderable: false, searchable: false },
                    { data: 'is_active', name: 'is_active' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
                order: [[1, 'desc']],
                pageLength: 25,
            });
        })
    </script>
@endsection
