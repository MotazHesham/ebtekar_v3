
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="AjaxModalLabel">{{ trans('global.extra.add_product') }}  {{ $order_num }}</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ route('admin.receipt-branches.add_product') }}" method="POST" enctype="multipart/form-data">
            @csrf  
            <input type="hidden" name="receipt_id" value="{{ $receipt_id }}">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-control select2 mb-2" name="product_id" id="product_id" required>
                        <option value="">أختر المنتج</option>
                        @foreach ($products as $product)
                            @if($product->$price_type > 0 )
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} - {{ $product->$price_type }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" type="number" name="quantity" step="1" min="1" required placeholder="{{ trans('global.extra.quantity') }}" required>
                    </div>
                </div>
            </div>
            <hr>
            
            <div class="form-group"> 
                <div class="row">
                    <div class="col-md-4">
                        <button type="submit" name="add_more" class="btn btn-success btn-block">حفظ وأضافة أخري</button>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="save_close" class="btn btn-dark btn-block">حفظ وألغاء</button>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.receipt-branches.index',['cancel_popup' => 1 ]) }}" class="btn btn-danger btn-block">ألغاء</a>
                    </div>
                </div>
            </div>
        </form>
    </div> 
</div> 

<script> 
    $('#product_id').select2({
        dropdownParent: $('#AjaxModal')
    });
</script>