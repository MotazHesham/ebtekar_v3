<div class="card">
    <div class="card-header">
        <div class="card">
            <div class="card-header">
                أضافة راتب
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.expenses.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="model_id" value="{{ $employee->id }}">
                    <input type="hidden" name="model_type" value="App\Models\Employee">
                    <input type="hidden" name="expense_category_id" value="1">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="required"
                                for="entry_date">{{ trans('cruds.expense.fields.entry_date') }}</label>
                            <input class="form-control date {{ $errors->has('entry_date') ? 'is-invalid' : '' }}"
                                type="text" name="entry_date" id="entry_date" value="{{ old('entry_date') }}"
                                required>
                            @if ($errors->has('entry_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entry_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.expense.fields.entry_date_helper') }}</span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="required" for="amount">أجمالي الراتب لشهر {{ date('F') }}</label>
                            <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number"
                                name="amount" id="amount" value="{{ old('amount', $employee->calc_financials(date('m'),date('Y'))) }}" step="0.01" required readonly>
                            @if ($errors->has('amount'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('amount') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.expense.fields.amount_helper') }}</span>
                        </div> 
                        <div class="form-group col-md-4">
                            <button class="btn btn-success btn-block" type="submit">
                                صرف الراتب
                            </button>
                            <button class="btn btn-danger btn-block" type="submit" name="download">
                                {{ trans('global.download') }}
                            </button>
                        </div>
                    </div> 
                </form>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Income">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.expense.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.expense.fields.entry_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.employee.fields.salery') }}
                        </th>
                        <th>
                            {{ trans('cruds.expense.fields.description') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $key => $expense)
                        <tr data-entry-id="{{ $expense->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $expense->id ?? '' }}
                            </td>
                            <td>
                                {{ $expense->entry_date ?? '' }}
                            </td>
                            <td>
                                {{ $expense->amount ?? '' }}
                            </td>
                            <td>
                                <?php echo $expense->description;  ?>
                            </td>
                            <td> 
                                @can('expense_delete')
                                    <form action="{{ route('admin.expenses.destroy', $expense->id) }}"
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
            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-Income:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
