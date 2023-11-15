<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="AjaxModalLabel">{{ trans('global.extra.edit_product') }}</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ route('admin.receipt-clients.edit_product') }}" method="POST" enctype="multipart/form-data">
            @csrf   
            <input type="hidden" name="receipt_product_pivot_id" value="{{ $receipt_client_product_pivot->id }}">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-control select2 mb-2" name="product_id" id="product_id" required>
                        <option value="">أختر المنتج</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @if($receipt_client_product_pivot->receipt_client_product_id == $product->id) selected @endif>
                                @if($product->$price_type > 0 )
                                    {{ $product->name }} - {{ $product->$price_type }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" type="number" name="quantity" value="{{ $receipt_client_product_pivot->quantity }}" step="1" min="1" placeholder="{{ trans('global.extra.quantity') }}" required>
                    </div>
                </div>
            </div>
            <hr>
            
            <div class="form-group"> 
                <button type="submit" class="btn btn-dark btn-block">{{ trans('global.save') }}</button>
            </div>
        </form>
    </div> 
</div>

<script> 
    $('#product_id').select2({
        dropdownParent: $('#AjaxModal2')
    });
</script>