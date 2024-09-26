@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.employeeFinancial.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.employee-financials.update", [$employeeFinancial->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="employee_id">{{ __('cruds.employeeFinancial.fields.employee') }}</label>
                <select class="form-control select2 {{ $errors->has('employee') ? 'is-invalid' : '' }}" name="employee_id" id="employee_id" required>
                    @foreach($employees as $id => $entry)
                        <option value="{{ $id }}" {{ (old('employee_id') ? old('employee_id') : $employeeFinancial->employee->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('employee'))
                    <div class="invalid-feedback">
                        {{ $errors->first('employee') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.employeeFinancial.fields.employee_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="financial_category_id">{{ __('cruds.employeeFinancial.fields.financial_category') }}</label>
                <select class="form-control select2 {{ $errors->has('financial_category') ? 'is-invalid' : '' }}" name="financial_category_id" id="financial_category_id" required>
                    @foreach($financial_categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('financial_category_id') ? old('financial_category_id') : $employeeFinancial->financial_category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('financial_category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('financial_category') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.employeeFinancial.fields.financial_category_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="amount">{{ __('cruds.employeeFinancial.fields.amount') }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', $employeeFinancial->amount) }}" step="0.01" required>
                @if($errors->has('amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.employeeFinancial.fields.amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="entry_date">{{ __('cruds.income.fields.entry_date') }}</label>
                <input class="form-control date {{ $errors->has('entry_date') ? 'is-invalid' : '' }}" type="text" name="entry_date" id="entry_date" value="{{ old('entry_date', $employeeFinancial->entry_date) }}" required>
                @if($errors->has('entry_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('entry_date') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.income.fields.entry_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reason">{{ __('cruds.employeeFinancial.fields.reason') }}</label>
                <textarea class="form-control {{ $errors->has('reason') ? 'is-invalid' : '' }}" name="reason" id="reason">{{ old('reason', $employeeFinancial->reason) }}</textarea>
                @if($errors->has('reason'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reason') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.employeeFinancial.fields.reason_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ __('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection