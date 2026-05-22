@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ $title }}</h4>
        <form class="form-inline" method="GET">
            <input type="date" name="date_from" class="form-control form-control-sm mr-2"
                value="{{ request('date_from') }}">
            <input type="date" name="date_to" class="form-control form-control-sm mr-2"
                value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-sm btn-primary">{{ __('global.filter') }}</button>
        </form>
    </div>

    <div class="row mb-4">
        @foreach ($dashboard['cards'] as $card)
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body">
                        <div class="text-value-lg">{{ $card['value'] }}</div>
                        <div>{{ $card['label'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">{{ __('reports::titles.by_status') }}</div>
                <div class="card-body">
                    <table class="table table-sm">
                        @forelse ($dashboard['by_status'] as $status => $count)
                            <tr>
                                <td>{{ __('delivery_order_status.' . $status) }}</td>
                                <td class="text-right"><strong>{{ $count }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-muted text-center">{{ __('global.no_results') }}</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">{{ __('reports::titles.recent') }}</div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('cruds.deliveryOrder.fields.order_num') }}</th>
                                @if ($dashboard['role'] === 'courier')
                                    <th>{{ __('cruds.deliveryOrder.fields.client_name') }}</th>
                                    <th>{{ __('cruds.deliveryOrder.fields.remaining_cod') }}</th>
                                @else
                                    <th>{{ __('cruds.deliveryOrder.fields.status') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dashboard['recent'] as $row)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.delivery-orders.show', $row->id) }}">{{ $row->order_num }}</a>
                                    </td>
                                    @if ($dashboard['role'] === 'courier')
                                        <td>{{ $row->client_name }}</td>
                                        <td>{{ number_format((float) ($row->remaining_cod ?? 0), 2) }}</td>
                                    @else
                                        <td>{{ __('delivery_order_status.' . $row->status) }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
