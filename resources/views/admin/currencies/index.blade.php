@extends('layouts.admin')
@section('content') 
    <div class="row">
        @foreach(\Cache::get('currency_rates') as $symbole => $value)
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body pb-0">
                        <div class="text-value">{{ $symbole }}</div>
                        <div>{{ $value }}</div>
                        <br />
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.currency.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Currency">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.currency.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.currency.fields.name') }}
                            </th>
                            <th>
                                {{ trans('cruds.currency.fields.symbol') }}
                            </th>
                            <th>
                                {{ trans('cruds.currency.fields.exchange_rate') }}
                            </th>
                            <th>
                                {{ trans('cruds.currency.fields.status') }}
                            </th>
                            <th>
                                {{ trans('cruds.currency.fields.code') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($currencies as $key => $currency)
                            <tr data-entry-id="{{ $currency->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $currency->id ?? '' }}
                                </td>
                                <td>
                                    {{ $currency->name ?? '' }}
                                </td>
                                <td>
                                    {{ $currency->symbol ?? '' }}
                                </td>
                                <td>
                                    {{ $currency->exchange_rate ?? '' }}
                                </td>
                                <td>
                                    <label class="c-switch c-switch-pill c-switch-success">
                                        <input onchange="update_statuses(this,'status')" value="{{ $currency->id }}"
                                            type="checkbox" class="c-switch-input"
                                            {{ $currency->status ? 'checked' : null }}>
                                        <span class="c-switch-slider"></span>
                                    </label>
                                </td>
                                <td>
                                    {{ $currency->code ?? '' }}
                                </td>
                                <td>
                                    @can('currency_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.currencies.show', $currency->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('currency_edit')
                                        <a class="btn btn-xs btn-info"
                                            href="{{ route('admin.currencies.edit', $currency->id) }}">
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
        function update_statuses(el,type){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.currencies.update_statuses') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status, type:type}, function(data){
                if(data == 1){
                    showAlert('success', 'Success', '');
                }else{
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
                pageLength: 100,
            });
            let table = $('.datatable-Currency:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
