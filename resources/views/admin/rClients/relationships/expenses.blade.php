<div class="card">
    <div class="card-header">
        <div class="card">
            <div class="card-header">
                أضافة دفعة
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.expenses.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="model_id" value="{{ $rClient->id }}">
                    <input type="hidden" name="model_type" value="App\Models\RClient">
                    <input type="hidden" name="expense_category_id" value="15">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="required"
                                for="entry_date">{{ __('cruds.expense.fields.entry_date') }}</label>
                            <input class="form-control date {{ $errors->has('entry_date') ? 'is-invalid' : '' }}"
                                type="text" name="entry_date" id="entry_date" value="{{ old('entry_date') }}"
                                required>
                            @if ($errors->has('entry_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entry_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ __('cruds.expense.fields.entry_date_helper') }}</span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="required" for="amount">{{ __('cruds.expense.fields.amount') }}</label>
                            <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number"
                                name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01" required>
                            @if ($errors->has('amount'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('amount') }}
                                </div>
                            @endif
                            <span class="help-block">{{ __('cruds.expense.fields.amount_helper') }}</span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="description">{{ __('cruds.expense.fields.description') }}</label>
                            <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                type="text" name="description" id="description"
                                value="{{ old('description', '') }}">
                            @if ($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ __('cruds.expense.fields.description_helper') }}</span>
                        </div>

                    </div>
                    <div class="form-group">
                        <button class="btn btn-success" type="submit">
                            {{ __('global.save') }}
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
                            {{ __('cruds.expense.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.expense.fields.entry_date') }}
                        </th>
                        <th>
                            {{ __('cruds.expense.fields.amount') }}
                        </th>
                        <th>
                            {{ __('cruds.expense.fields.description') }}
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
                                {{ $expense->description ?? '' }}
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
