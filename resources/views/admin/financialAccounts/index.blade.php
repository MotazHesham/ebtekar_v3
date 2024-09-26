@extends('layouts.admin')
@section('content')
    @can('financial_account_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.financial-accounts.create') }}">
                    {{ __('global.add') }} {{ __('cruds.financialAccount.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ __('cruds.financialAccount.title_singular') }} {{ __('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-FinancialAccount">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ __('cruds.financialAccount.fields.id') }}
                            </th>
                            <th>
                                {{ __('cruds.financialAccount.fields.account') }}
                            </th>
                            <th>
                                {{ __('cruds.financialAccount.fields.description') }}
                            </th>
                            <th>
                                {{ __('cruds.financialAccount.fields.active') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($financialAccounts as $key => $financialAccount)
                            <tr data-entry-id="{{ $financialAccount->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $financialAccount->id ?? '' }}
                                </td>
                                <td>
                                    {{ $financialAccount->account ?? '' }}
                                </td>
                                <td>
                                    {{ $financialAccount->description ?? '' }}
                                </td>
                                <td>
                                    <label class="c-switch c-switch-pill c-switch-success">
                                        <input onchange="update_statuses(this,'active')"
                                            value="{{ $financialAccount->id }}" type="checkbox" class="c-switch-input"
                                            {{ $financialAccount->active ? 'checked' : null }}>
                                        <span class="c-switch-slider"></span>
                                    </label>
                                </td>
                                <td>
                                    @can('financial_account_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.financial-accounts.show', $financialAccount->id) }}">
                                            {{ __('global.view') }}
                                        </a>
                                    @endcan

                                    @can('financial_account_edit')
                                        <a class="btn btn-xs btn-info"
                                            href="{{ route('admin.financial-accounts.edit', $financialAccount->id) }}">
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
        function update_statuses(el, type) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('admin.financial-accounts.update_statuses') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status,
                type: type
            }, function(data) {
                if (data == 1) {
                    showAlert('success', 'Success', '');
                } else {
                    showAlert('danger', 'Something went wrong', '');
                }
            });
        }

        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons) 

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            });
            let table = $('.datatable-FinancialAccount:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
