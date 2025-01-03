<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="AjaxModalLabel">{{ __('global.extra.edit_product') }}</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ route('admin.receipt-price-views.edit_product') }}" method="POST" enctype="multipart/form-data">
            @csrf   
            <input type="hidden" name="receipt_price_view_product_id" value="{{ $receipt_price_view_product->id }}">
            <div class="row"> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">{{ __('global.extra.product') }}</label> 
                        <input class="form-control" type="text" name="description" value="{{ $receipt_price_view_product->description }}" step="1" min="1" placeholder="{{ __('global.extra.description') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">{{ __('global.extra.price') }}</label>
                        <input class="form-control" type="number" name="price" value="{{ $receipt_price_view_product->price }}" step="1" min="1" placeholder="{{ __('global.extra.price') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">{{ __('global.extra.quantity') }}</label>
                        <input class="form-control" type="number" name="quantity" value="{{ $receipt_price_view_product->quantity }}" step="1" min="1" placeholder="{{ __('global.extra.quantity') }}" required>
                    </div>
                </div>
            </div>
            <hr>
            
            <div class="form-group"> 
                <button type="submit" class="btn btn-dark btn-block">{{ __('global.save') }}</button>
            </div>
        </form>
    </div> 
</div> 