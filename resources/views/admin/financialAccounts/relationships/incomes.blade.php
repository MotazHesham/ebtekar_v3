<div class="card">
    <div class="card-header">
        <div class="card">
            <div class="card-header">
                أضافة سحب
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.incomes.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="model_id" value="{{ $financialAccount->id }}">
                    <input type="hidden" name="model_type" value="App\Models\FinancialAccount">
                    <input type="hidden" name="income_category_id" value="6">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="required"
                                for="entry_date">{{ trans('cruds.income.fields.entry_date') }}</label>
                            <input class="form-control date {{ $errors->has('entry_date') ? 'is-invalid' : '' }}"
                                type="text" name="entry_date" id="entry_date" value="{{ old('entry_date') }}"
                                required>
                            @if ($errors->has('entry_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entry_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.income.fields.entry_date_helper') }}</span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="required" for="amount">{{ trans('cruds.income.fields.amount') }}</label>
                            <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number"
                                name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01" required>
                            @if ($errors->has('amount'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('amount') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.income.fields.amount_helper') }}</span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="description">{{ trans('cruds.income.fields.description') }}</label>
                            <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                type="text" name="description" id="description"
                                value="{{ old('description', '') }}">
                            @if ($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.income.fields.description_helper') }}</span>
                        </div>

                    </div>
                    <div class="form-group">
                        <button class="btn btn-success" type="submit">
                            {{ trans('global.save') }}
                        </button>
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
                            {{ trans('cruds.income.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.income.fields.entry_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.income.fields.amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.income.fields.description') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($incomes as $key => $income)
                        <tr data-entry-id="{{ $income->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $income->id ?? '' }}
                            </td>
                            <td>
                                {{ $income->entry_date ?? '' }}
                            </td>
                            <td>
                                {{ $income->amount ?? '' }}
                            </td>
                            <td>
                                {{ $income->description ?? '' }}
                            </td>
                            <td> 
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
