@if (count($combinations[0]) > 0)
    <div class="card">
        <div class="card-header">{{ trans('cruds.product.fields.attribute_options') }}</div>
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <td class="text-center">
                            <label for="" class="control-label">{{ trans('cruds.product.fields.variant_product') }}</label>
                        </td>
                        <td class="text-center">
                            <label for="" class="control-label">{{ trans('cruds.product.fields.unit_price') }}</label>
                        </td>
                        <td class="text-center">
                            <label for="" class="control-label">{{ trans('cruds.product.fields.purchase_price') }}</label>
                        </td>
                        <td class="text-center">
                            <label for="" class="control-label">{{ trans('cruds.product.fields.current_stock') }}</label>
                        </td>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($combinations as $key => $combination)
                        @php
                            $str = '';
                            foreach ($combination as $key => $item) {
                                if ($key > 0) {
                                    $str .= '-' . str_replace(' ', '', $item);
                                } else {
                                    if ($colors_active == 1) {
                                        $color_name = \App\Models\Color::where('code', $item)->first()->name;
                                        $str .= $color_name;
                                    } else {
                                        $str .= str_replace(' ', '', $item);
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
                                    <input type="number" name="unit_price_{{ $str }}"
                                        value="@php 
										if ($product->unit_price == $unit_price) {
											if(($stock = $product->stocks()->where('variant', $str)->first()) != null){
												echo $stock->unit_price;
											}else{
												echo $unit_price;
											}
										}
										else{
											echo $unit_price;
										} @endphp"
                                        min="0" step="0.01" class="form-control" required>
                                </td>
                                <td>
                                    <input type="number" name="purchase_price_{{ $str }}"
                                        value="@php 
										if ($product->purchase_price == $purchase_price) {
											if(($stock = $product->stocks()->where('variant', $str)->first()) != null){
												echo $stock->purchase_price;
											}else{
												echo $purchase_price;
											}	
										}else{
											echo $purchase_price;
										} @endphp"
                                        min="0" step="0.01" class="form-control" required>
                                </td>
                                <td>
                                    <input type="number" name="stock_{{ $str }}"
                                        value="@php 
										if(($stock = $product->stocks()->where('variant', $str)->first()) != null){
											echo $stock->stock;
										}
										else{
											echo '10';
										} @endphp"
                                        min="0" step="1" class="form-control" required>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endif
