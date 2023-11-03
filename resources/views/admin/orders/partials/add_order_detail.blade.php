
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">أضافة منتج إلي أوردر {{ $order->order_num }}</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        
        <form method="POST" action="{{ route("admin.orders.store_order_detail") }}" enctype="multipart/form-data"> 
            @csrf  
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            
            <h5 class="mb-3">{{ trans('frontend.product.printed_photos') }}</h5>
            <div id="product-images">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input type="file" id="photos-1" name="photos[]" class="form-control"> 
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" name="photos_note[]" class="form-control" id="name" placeholder="{{ trans('frontend.product.photo_note') }}" >
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-warning mb-3" onclick="add_more_slider_image()">{{ trans('frontend.product.add_more') }}</button>

            <div class="col-12 mb-3">
                <label>{{ trans('frontend.product.description') }}</label>
                <textarea class="form-control" name="description" placeholder="{{ trans('frontend.product.description') }}" rows="3" required></textarea>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label class="required" for="product_id">{{ trans('cruds.product.fields.name') }}</label> 
                    <select class="form-control select2 " name="product_id" id="product_id" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{$product->unit_price}}"><span>{{ $product->name }}</span> -  <b>({{ $product->unit_price }})</b></option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="price">{{ trans('frontend.product.cost') }}</label>
                    <input class="form-control" type="number" name="price" id="price"  required> 
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="quantity">{{ trans('frontend.product.quantity') }}</label>
                    <input class="form-control" type="number" name="quantity" id="quantity" required min="1" > 
                </div>
                <div class="form-group col-md-4">
                    <label class="required">{{ trans('cruds.order.extra.extra_commission') }}</label>
                    <input class="form-control" type="number" name="extra_commission" id="extra_commission" > 
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
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