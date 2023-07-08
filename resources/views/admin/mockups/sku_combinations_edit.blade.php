@if (count($combinations[0]) > 0)
    <table class="table table-bordered">
        <thead>
            <tr>
                <td class="text-center">
                    <label for="" class="control-label">النوع</label>
                </td>
                <td class="text-center">
                    <label for="" class="control-label">السعر</label>
                </td>
                <td class="text-center">
                    <label for="" class="control-label">الكمية في المخزن</label>
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
                            <input type="number" name="purchase_price_{{ $str }}"
                                value="@php
										if ($mockup->purchase_price == $purchase_price) {
											if(($stock = $mockup->stocks->where('variant', $str)->first()) != null){
												echo $stock->price;
											}
											else{
												echo $purchase_price;
											}
										}
										else{
											echo $purchase_price;
										} @endphp"
                                min="0" step="0.01" class="form-control" required>
                        </td>

                        <td>
                            <input type="number" name="quantity_{{ $str }}"
                                value="@php
										if(($stock = $mockup->stocks->where('variant', $str)->first()) != null){
											echo $stock->quantity;
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
@endif
