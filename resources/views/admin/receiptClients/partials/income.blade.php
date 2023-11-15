<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">صرف جزء من الأذن</h5>
        &nbsp;&nbsp;&nbsp;
        <a href="{{ route('admin.receipt-clients.index', ['cancel_popup' => 1]) }}"
            class="btn btn-danger btn-lg mb-1">أغلاق القائمة</a>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form method="POST" action="{{ route('admin.incomes.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="model_id" value="{{ $receipt->id }}">
            <input type="hidden" name="model_type" value="App\Models\ReceiptClient">
            <input type="hidden" name="income_category_id" value="1">
            <div class="row">
                <div class="form-group col-md-4">
                    <label class="required" for="entry_date">{{ trans('cruds.income.fields.entry_date') }}</label>
                    <input class="form-control date {{ $errors->has('entry_date') ? 'is-invalid' : '' }}" type="text"
                        name="entry_date" id="entry_date" value="{{ old('entry_date') }}" required>
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
                    <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text"
                        name="description" id="description" value="{{ old('description', '') }}">
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
