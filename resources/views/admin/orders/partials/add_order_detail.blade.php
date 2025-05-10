
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">أضافة منتج إلي أوردر {{ $order->order_num }}</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        
        <form method="POST" action="{{ route("admin.orders.store_order_detail") }}" enctype="multipart/form-data"> 
            @csrf  
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            
            <h5 class="mb-3">{{ __('frontend.product.printed_photos') }}</h5>
            <div id="product-images">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input type="file" id="photos-1" name="photos[]" class="form-control"> 
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" name="photos_note[]" class="form-control" id="name" placeholder="{{ __('frontend.product.photo_note') }}" >
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-warning mb-3" onclick="add_more_slider_image()">{{ __('frontend.product.add_more') }}</button>

            <div class="col-12 mb-3">
                <label>{{ __('frontend.product.description') }}</label>
                <textarea class="form-control" name="description" placeholder="{{ __('frontend.product.description') }}" rows="3" required></textarea>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label class="required" for="product_id">{{ __('cruds.product.fields.name') }}</label> 
                    <select class="form-control select2 " name="product_id" id="product_id" required>
                        @foreach($products as $product)
                            @if($product->variant_product)
                                <optgroup label="{{ $product->name }}">
                                    @foreach($product->stocks as $stock)
                                    <option value="{{ $product->id }}" 
                                            data-variation="{{ $stock->variant }}"
                                            data-price="{{front_calc_product_currency($product->calc_discount($stock->unit_price),$product->weight)['value']}}">
                                        <span> -- {{ $product->name }} - ({{ $stock->variant }})</span>
                                        - <b>({{ front_calc_product_currency($product->calc_discount($stock->unit_price),$product->weight)['value'] }})</b>
                                    </option>
                                    @endforeach
                                </optgroup>
                            @else
                                <option value="{{ $product->id }}" 
                                        data-price="{{front_calc_product_currency($product->calc_discount($product->unit_price),$product->weight)['value']}}">
                                    <span>{{ $product->name }}</span>
                                    - <b>({{ front_calc_product_currency($product->calc_discount($product->unit_price),$product->weight)['value'] }})</b>
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="price">{{ __('frontend.product.cost') }}</label>
                    <input class="form-control" type="number" name="price" id="price"  required> 
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="quantity">{{ __('frontend.product.quantity') }}</label>
                    <input class="form-control" type="number" name="quantity" id="quantity" required min="1" > 
                </div>
                <div class="form-group col-md-4">
                    <label >{{ __('cruds.order.extra.extra_commission') }}</label>
                    <input class="form-control" type="number" name="extra_commission" id="extra_commission" > 
                </div>
                <div class="form-group col-md-4">
                    <label >{{ __('cruds.order.extra.variation') }}</label>
                    <input class="form-control" type="text" name="variation" id="variation" > 
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ __('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script> 
    $('#product_id').select2({
        dropdownParent: $('#AjaxModal')
    });
    $('#product_id').on('change',function(){
        let price = $(this).find(':selected').attr('data-price');
        $("#price").val(price);
        let variation = $(this).find(':selected').attr('data-variation');
        $("#variation").val(variation);
    });

    
    var photo_id = 2;
    function add_more_slider_image(){
        console.log('test');
        var photoAdd =  '<div class="row">'; 
        photoAdd += '<div class="col-md-1">';
        photoAdd += '<button type="button" onclick="delete_this_row(this)" class="btn btn-danger"><i class="fa fa-trash"></i></button>';
        photoAdd += '</div>';
        photoAdd += '<div class="col-md-7 mb-3">';
        photoAdd += '<input type="file" id="photos-'+photo_id+'" name="photos[]" class="form-control" multiple accept="image/*" />'; 
        photoAdd += '</div>';
        photoAdd += '<div class="col-md-4 mb-3">';
        photoAdd += '<input type="text" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة" >';
        photoAdd += '</div>';
        photoAdd += '</div>'; 
        $('#product-images').append(photoAdd);

        photo_id++; 
    } 
    function delete_this_row(em){
        $(em).closest('.row').remove();
    }
</script>