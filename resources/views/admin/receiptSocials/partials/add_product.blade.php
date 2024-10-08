
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="AjaxModalLabel">{{ __('global.extra.add_product') }} {{ $order_num }} </h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ route('admin.receipt-socials.add_product') }}" method="POST" enctype="multipart/form-data">
            @csrf  
            <input type="hidden" name="receipt_id" value="{{ $receipt_id }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('global.extra.product') }}</label> 
                        <select class="form-control select2 mb-2" name="product_id" id="product_id" required>
                            <option value="">أختر المنتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} - {{ $product->price }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('global.extra.quantity') }}</label>
                        <input class="form-control" type="number" name="quantity" step="1" min="1" required placeholder="{{ __('global.extra.quantity') }}" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <textarea class="form-control ckeditor" name="description" placeholder="{{ __('global.extra.description') }}"  cols="30" rows="6"></textarea>
            </div>
            <div class="form-group"> 
                <label>PDF</label>
                <input type="file" class="form-control" name="pdf">
            </div>
            <div class="form-group"> 
                <div id="product-images">
                    <label>{{ __('cruds.receiptSocialProduct.fields.photos') }}</label>
                    <div class="row"> 
                        <div class="col-md-7">
                            <input type="file" name="photos[][photo]" id="photos-1" class="custom-input-file custom-input-file--4 form-control" data-multiple-caption="{count} files selected" accept="image/*" />
                            <label for="photos-1" class="mw-100 mb-3">
                                <span></span> 
                            </label>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="photos[][note]" class="form-control" placeholder="{{ __('global.extra.photo_note') }}">
                        </div> 
                    </div>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-info mb-3" onclick="add_more_slider_image()">{{ __('global.extra.add_more') }}</button>
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
                        <a href="{{ route('admin.receipt-socials.index',['cancel_popup' => 1 ]) }}" class="btn btn-danger btn-block">ألغاء</a>
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