<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('Creator shift metrics') }} #{{ $shift->id }}
        </h5>
        <button type="button" class="close" data-dismiss="modal"
            aria-label="{{ __('Close') }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        @php
            $sales = $metrics['sales'] ?? [];
            $products = $sales['products_breakdown'] ?? [];
        @endphp

        <div class="row mb-3">
            <div class="col-md-4">
                <p>
                    <strong>{{ __('Receipts count') }}:</strong>
                    {{ $sales['receipts_count'] ?? 0 }}
                </p>
            </div>
            <div class="col-md-4">
                <p>
                    <strong>{{ __('Products count') }}:</strong>
                    {{ $sales['products_count'] ?? 0 }}
                </p>
            </div>
            <div class="col-md-4">
                <p>
                    <strong>{{ __('Total revenue') }}:</strong>
                    {{ number_format($sales['total_revenue'] ?? 0, 2) }}
                </p>
            </div>
        </div>

        @if (!empty($products))
            <hr>
            <h6>{{ __('Products in this shift') }}</h6>
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Photo') }}</th>
                            <th>{{ __('Product name') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Total revenue') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    @if (!empty($product['photo']))
                                        <img src="{{ asset($product['photo']) }}" alt="{{ $product['product_name'] }}"
                                            style="height: 50px; width: 50px; object-fit: cover;">
                                    @endif
                                </td>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['quantity'] }}</td>
                                <td>{{ number_format($product['total_revenue'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

