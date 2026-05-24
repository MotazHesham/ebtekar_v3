@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">{{ __('global.edit') }} — {{ __('returns::titles.detail') }} #{{ $returnCase->id }}</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.returns.update', $returnCase) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>{{ __('cruds.deliveryOrder.fields.return_reason') }}</label>
                    <select name="reason" class="form-control">
                        @foreach ($returnReasons as $key => $label)
                            <option value="{{ $key }}" @selected($returnCase->reason === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ __('returns::fields.note') }}</label>
                    <textarea name="note" class="form-control" rows="3">{{ old('note', $returnCase->note) }}</textarea>
                </div>
                <div class="form-group">
                    <label>{{ __('returns::fields.case_status') }}</label>
                    <select name="status" class="form-control">
                        @foreach ($caseStatuses as $status)
                            <option value="{{ $status->value }}" @selected($returnCase->status === $status->value)>
                                {{ __('returns::case_status.' . $status->value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success">{{ __('global.update') }}</button>
                <a href="{{ route('admin.returns.show', $returnCase) }}" class="btn btn-secondary">{{ __('global.back') }}</a>
            </form>
        </div>
    </div>
@endsection
