@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('Shifts') }}
        </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-info">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('admin.shifts.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="from_date">{{ __('From date') }}</label>
                            <input type="text" name="from_date" id="from_date" class="form-control date"
                                   value="{{ $fromDate }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="to_date">{{ __('To date') }}</label>
                            <input type="text" name="to_date" id="to_date" class="form-control date"
                                   value="{{ $toDate }}">
                        </div>
                    </div>
                    @if($isAdmin)
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="employee_id">{{ __('Employee') }}</label>
                            <select name="employee_id" id="employee_id" class="form-control select2">
                                <option value="">{{ __('All employees') }}</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ (string) $selectedEmployeeId === (string) $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">{{ __('Shift type') }}</label>
                            <select name="type" id="type" class="form-control" required>
                                @foreach ($shiftTypes as $type => $label)
                                    <option value="{{ $type }}"
                                        {{ $selectedType === $type ? 'selected' : '' }}>
                                        {{ ucfirst($label) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary mr-2">
                        {{ __('Filter') }}
                    </button>
                    <a href="{{ route('admin.shifts.index') }}" class="btn btn-secondary">
                        {{ __('Reset') }}
                    </a>
                </div>
            </form>

            @if ($globalMetrics)
                <div class="mb-4">
                    <h5>{{ __('Global metrics for filtered shifts') }}</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <strong>{{ __('Total receipts') }}:</strong>
                                    {{ $globalMetrics['receipts_count'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <strong>{{ __('Total products') }}:</strong>
                                    {{ $globalMetrics['products_count'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                        @if ($isAdmin)
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <strong>{{ __('Total revenue') }}:</strong>
                                        {{ number_format($globalMetrics['total_revenue'] ?? 0, 2) }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <h5>{{ __('Shifts list') }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Employee') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Shift date') }}</th>
                            <th>{{ __('Started at') }}</th>
                            <th>{{ __('Ended at') }}</th>
                            <th>{{ __('Status') }}</th> 
                            <th>{{ __('Metrics') }}</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shifts as $shift)
                            <tr>
                                <td>{{ $shift->id }}</td>
                                <td>{{ optional($shift->user)->name }}</td>
                                <td>{{ $shift->type }}</td>
                                <td>{{ $shift->shift_date }}</td>
                                <td>{{ $shift->started_at }}</td>
                                <td>{{ $shift->ended_at }}</td>
                                <td>{{ $shift->status }}</td> 
                                <td>
                                    @if($shift->metrics)
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                            onclick="viewShiftMetrics('{{ $shift->id }}')">
                                            {{ __('View metrics') }}
                                        </button>
                                    @else
                                        <span class="text-muted">{{ __('No metrics') }}</span>
                                    @endif
                                </td> 
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">{{ __('No shifts found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $shifts->links() }}
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        function viewShiftMetrics(shiftId) {
            $.post('{{ route('admin.shifts.metrics') }}', {
                _token: '{{ csrf_token() }}',
                id: shiftId
            }, function(response) {
                if (response.html) {
                    $('#AjaxModal .modal-dialog').html(null);
                    $('#AjaxModal').modal('show');
                    $('#AjaxModal .modal-dialog').html(response.html);
                }
            });
        }
    </script>
@endsection

