<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('Operation shift metrics') }} #{{ $shift->id }}
        </h5>
        <button type="button" class="close" data-dismiss="modal"
            aria-label="{{ __('Close') }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        @php
            $operations = $metrics['operations'] ?? [];
            $perStage = $operations['operations_per_stage'] ?? [];
            $avgPerStage = $operations['avg_duration_per_stage_s'] ?? [];
        @endphp

        <div class="row mb-3">
            <div class="col-md-4">
                <p>
                    <strong>{{ __('Operations completed') }}:</strong>
                    {{ $operations['operations_completed'] ?? 0 }}
                </p>
            </div>
            <div class="col-md-4">
                <p>
                    <strong>{{ __('Total workload (seconds)') }}:</strong>
                    {{ $operations['total_workload_seconds'] ?? 0 }}
                </p>
            </div>
        </div>

        @if (!empty($perStage))
            <hr>
            <h6>{{ __('Operations per stage') }}</h6>
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Stage') }}</th>
                            <th>{{ __('Operations count') }}</th>
                            <th>{{ __('Avg duration (seconds)') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perStage as $stage => $count)
                            <tr>
                                <td>{{ $stage }}</td>
                                <td>{{ $count }}</td>
                                <td>{{ isset($avgPerStage[$stage]) ? round($avgPerStage[$stage], 2) : 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

