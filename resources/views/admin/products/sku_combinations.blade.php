@if(count($combinations[0]) > 0)
<div class="card">
    <div class="card-header">{{ __('cruds.product.fields.attribute_options') }}</div>
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <td class="text-center">
                        <label for="" class="control-label">{{ __('cruds.product.fields.variant_product') }}</label>
                    </td>
                    <td class="text-center">
                        <label for="" class="control-label">{{ __('cruds.product.fields.unit_price') }}</label>
                    </td>
                    <td class="text-center">
                        <label for="" class="control-label">{{ __('cruds.product.fields.purchase_price') }}</label>
                    </td>
                    <td class="text-center">
                        <label for="" class="control-label">{{ __('cruds.product.fields.current_stock') }}</label>
                    </td>
                </tr>
            </thead>
            <tbody>
                @foreach ($combinations as $key => $combination)
                    @php
                        $sku = '';
                        foreach (explode(' ', $product_name) as $key => $value) {
                            $sku .= substr($value, 0, 1);
                        }
                        
                        $str = '';
                        foreach ($combination as $key => $item) {
                            if ($key > 0) {
                                $str .= '-' . str_replace(' ', '', $item);
                                $sku .= '-' . str_replace(' ', '', $item);
                            } else {
                                if ($colors_active == 1) {
                                    $color_name = \App\Models\Color::where('code', $item)->first()->name;
                                    $str .= $color_name;
                                    $sku .= '-' . $color_name;
                                } else {
                                    $str .= str_replace(' ', '', $item);
                                    $sku .= '-' . str_replace(' ', '', $item);
                                }
                            }
                        }
                    @endphp
                    @if (strlen($str) > 0)
                        <tr>
                            <td>
                                <label for="" class="control-label">{{ $str }}</label>
                            </td>
                            <td>
                                <input type="number" name="unit_price_{{ $str }}" value="{{ $unit_price }}" min="0" step="0.01" class="form-control" required>
                            </td>
                            <td>
                                <input type="number" name="purchase_price_{{ $str }}" value="{{ $purchase_price }}" min="0" step="0.01" class="form-control" required>
                            </td>
                            <td>
                                <input type="number" name="stock_{{ $str }}" value="10" min="0" step="1" class="form-control" required>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endif
