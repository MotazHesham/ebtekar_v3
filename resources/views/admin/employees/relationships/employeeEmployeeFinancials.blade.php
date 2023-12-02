@can('employee_financial_create')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.employee-financials.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="required"
                            for="financial_category_id">{{ trans('cruds.employeeFinancial.fields.financial_category') }}</label>
                        <select class="form-control select2 {{ $errors->has('financial_category') ? 'is-invalid' : '' }}"
                            name="financial_category_id" id="financial_category_id" required>
                            @foreach ($financial_categories as $id => $entry)
                                <option value="{{ $id }}"
                                    {{ old('financial_category_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('financial_category'))
                            <div class="invalid-feedback">
                                {{ $errors->first('financial_category') }}
                            </div>
                        @endif
                        <span
                            class="help-block">{{ trans('cruds.employeeFinancial.fields.financial_category_helper') }}</span>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="required" for="amount">{{ trans('cruds.employeeFinancial.fields.amount') }}</label>
                        <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number"
                            name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01" required>
                        @if ($errors->has('amount'))
                            <div class="invalid-feedback">
                                {{ $errors->first('amount') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.employeeFinancial.fields.amount_helper') }}</span>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="required" for="entry_date">{{ trans('cruds.income.fields.entry_date') }}</label>
                        <input class="form-control date {{ $errors->has('entry_date') ? 'is-invalid' : '' }}" type="text" name="entry_date" id="entry_date" value="{{ old('entry_date') }}" required>
                        @if($errors->has('entry_date'))
                            <div class="invalid-feedback">
                                {{ $errors->first('entry_date') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.income.fields.entry_date_helper') }}</span>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="reason">{{ trans('cruds.employeeFinancial.fields.reason') }}</label>
                        <textarea class="form-control {{ $errors->has('reason') ? 'is-invalid' : '' }}" name="reason" id="reason">{{ old('reason') }}</textarea>
                        @if ($errors->has('reason'))
                            <div class="invalid-feedback">
                                {{ $errors->first('reason') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.employeeFinancial.fields.reason_helper') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit" name="single_employee">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.employeeFinancial.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table
                class=" table table-bordered table-striped table-hover datatable datatable-employeeEmployeeFinancials">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.employeeFinancial.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.employeeFinancial.fields.financial_category') }}
                        </th>
                        <th>
                            {{ trans('cruds.employeeFinancial.fields.amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.employeeFinancial.fields.reason') }}
                        </th>
                        <th>
                            {{ trans('cruds.income.fields.entry_date') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employeeFinancials as $key => $employeeFinancial)
                        <tr data-entry-id="{{ $employeeFinancial->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $employeeFinancial->id ?? '' }}
                            </td>
                            <td>
                                {{ $employeeFinancial->financial_category->name ?? '' }}
                            </td>
                            <td>
                                {{ $employeeFinancial->amount ?? '' }}
                            </td>
                            <td>
                                {{ $employeeFinancial->reason ?? '' }}
                            </td>
                            <td>
                                {{ $employeeFinancial->entry_date ?? '' }}
                            </td>
                            <td>
                                @can('employee_financial_show')
                                    <a class="btn btn-xs btn-primary"
                                        href="{{ route('admin.employee-financials.show', $employeeFinancial->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('employee_financial_edit')
                                    <a class="btn btn-xs btn-info"
                                        href="{{ route('admin.employee-financials.edit', $employeeFinancial->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('employee_financial_delete')
                                    <form action="{{ route('admin.employee-financials.destroy', $employeeFinancial->id) }}"
                                        method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                        style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger"
                                            value="{{ trans('global.delete') }}">
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

@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('employee_financial_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.employee-financials.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
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

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            });
            let table = $('.datatable-employeeEmployeeFinancials:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
