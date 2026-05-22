@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">{{ __('settlement::actions.open_new') }}</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settlements.store') }}" id="open-settlement-form">
                @csrf
                <div class="form-group">
                    <label>{{ __('cruds.deliveryOrder.fields.courier') }}</label>
                    <select name="courier_id" id="courier_id" class="form-control demo-select2" required>
                        <option value="">{{ __('settlement::actions.select_courier') }}</option>
                        @foreach ($couriers as $courier)
                            <option value="{{ $courier->id }}" @selected(old('courier_id') == $courier->id)>
                                {{ $courier->user?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ __('settlement::fields.date') }}</label>
                    <input type="date" name="settlement_date" class="form-control"
                        value="{{ old('settlement_date', now()->toDateString()) }}">
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" name="include_all_unsettled" value="1"
                        id="include_all_unsettled" @checked(old('include_all_unsettled'))>
                    <label class="form-check-label" for="include_all_unsettled">
                        {{ __('settlement::fields.include_all_unsettled') }}
                    </label>
                </div>
                <div class="alert alert-info" id="preview-box" style="display:none">
                    {{ __('settlement::messages.preview') }}:
                    <strong id="preview-count">0</strong> {{ __('settlement::fields.orders') }} —
                    <strong id="preview-amount">0</strong>
                </div>
                <button type="submit" class="btn btn-primary" id="btn-open" disabled>
                    {{ __('settlement::actions.open') }}
                </button>
                <a href="{{ route('admin.settlements.index') }}" class="btn btn-secondary">{{ __('global.back') }}</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        function refreshPreview() {
            let courierId = $('#courier_id').val();
            if (!courierId) {
                $('#preview-box').hide();
                $('#btn-open').prop('disabled', true);
                return;
            }
            $.get('{{ route('admin.settlements.preview') }}', {
                courier_id: courierId,
                settlement_date: $('input[name=settlement_date]').val(),
                include_all_unsettled: $('#include_all_unsettled').is(':checked') ? 1 : 0
            }, function (data) {
                $('#preview-count').text(data.count);
                $('#preview-amount').text(data.expected_amount);
                $('#preview-box').show();
                $('#btn-open').prop('disabled', data.count === 0);
            });
        }

        $('#courier_id, input[name=settlement_date], #include_all_unsettled').on('change', refreshPreview);
    </script>
@endsection
