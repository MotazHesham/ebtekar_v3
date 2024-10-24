<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">صرف جزء من الأذن</h5>
        &nbsp;&nbsp;&nbsp;
        <a href="{{ route('admin.receipt-branches.index', ['cancel_popup' => 1]) }}"
            class="btn btn-danger btn-lg mb-1">أغلاق القائمة</a>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        @if()
        <form method="POST" action="{{ $receipt->branch->type == 'income' ? route('admin.incomes.store') : route('admin.expenses.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="model_id" value="{{ $receipt->id }}">
            <input type="hidden" name="model_type" value="App\Models\ReceiptBranch">
            <input type="hidden" @if($receipt->branch->type == 'income') name="income_category_id" value="1" @else name="expense_category_id"  value="15" @endif >
            <div class="row">
                <div class="form-group col-md-4">
                    <label class="required" for="entry_date">{{ __('cruds.income.fields.entry_date') }}</label>
                    <input class="form-control date {{ $errors->has('entry_date') ? 'is-invalid' : '' }}" type="text"
                        name="entry_date" id="entry_date" value="{{ old('entry_date') }}" required>
                    @if ($errors->has('entry_date'))
                        <div class="invalid-feedback">
                            {{ $errors->first('entry_date') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.income.fields.entry_date_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="amount">{{ __('cruds.income.fields.amount') }}</label>
                    <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number"
                        name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01" required>
                    @if ($errors->has('amount'))
                        <div class="invalid-feedback">
                            {{ $errors->first('amount') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.income.fields.amount_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="description">{{ __('cruds.income.fields.description') }}</label>
                    <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text"
                        name="description" id="description" value="{{ old('description', '') }}">
                    @if ($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.income.fields.description_helper') }}</span>
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
<script>
    $('.date').datetimepicker({
        format: 'DD/MM/YYYY',
        locale: 'en',
        icons: {
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right'
        }
    })
</script>
