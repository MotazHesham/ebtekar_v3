
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
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="type">النوع</label>
                        <select class="form-control" name="type" id="product_type_select" required>
                            <option value="single">فردي</option>
                            <option value="box">بوكس</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Single Product Section -->
            <div id="single_product_section">
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
            </div>
            
            <!-- Box Product Section -->
            <div id="box_product_section" style="display: none;"> 
                <div class="form-group">
                    <label>منتجات البوكس</label>
                    <div id="box_products_container">
                        <div class="box-product-row mb-3 p-3 border">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>المنتج</label>
                                        <select class="form-control select2 box-product-select" name="box_products[0][product_id]">
                                            <option value="">أختر المنتج</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->box_price }}">
                                                    {{ $product->name }} - {{ $product->box_price }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>الكمية</label>
                                        <input class="form-control" type="number" name="box_products[0][quantity]" step="1" min="1" value="1">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>السعر</label>
                                        <input class="form-control box-price-input" type="number" name="box_products[0][price]" step="0.01" min="0">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-block remove-box-product" onclick="removeBoxProduct(this)">حذف</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-info" onclick="addBoxProduct()">إضافة منتج للبوكس</button>
                </div>
            </div>
            <div class="form-group">
                <textarea class="form-control ckeditor" name="description" placeholder="{{ __('global.extra.description') }}"  cols="30" rows="6"></textarea>
            </div>
            <div class="form-group"> 
                <label>PDF</label>
                <input type="file" class="form-control" name="pdf[]" multiple>
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
    
    var boxProductIndex = 1;
    
    $('#product_type_select').on('change', function() {
        if ($(this).val() == 'single') {
            $('#single_product_section').show();
            $('#box_product_section').hide();
            $('#single_product_section input, #single_product_section select').prop('required', true);
            $('#box_product_section input, #box_product_section select').prop('required', false);
        } else {
            $('#single_product_section').hide();
            $('#box_product_section').show();
            $('#single_product_section input, #single_product_section select').prop('required', false);
            $('#box_product_section input, #box_product_section select').prop('required', true);
        }
    });
    
    function addBoxProduct() {
        const html = `
            <div class="box-product-row mb-3 p-3 border">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>المنتج</label>
                            <select class="form-control select2 box-product-select" name="box_products[${boxProductIndex}][product_id]" required>
                                <option value="">أختر المنتج</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->box_price }}">
                                        {{ $product->name }} - {{ $product->box_price }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>الكمية</label>
                            <input class="form-control" type="number" name="box_products[${boxProductIndex}][quantity]" step="1" min="1" value="1" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>السعر</label>
                            <input class="form-control box-price-input" type="number" name="box_products[${boxProductIndex}][price]" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-block remove-box-product" onclick="removeBoxProduct(this)">حذف</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#box_products_container').append(html);
        $('#box_products_container .select2').last().select2({
            dropdownParent: $('#AjaxModal')
        });
        
        // Auto-fill price when product is selected
        $('#box_products_container .box-product-select').last().on('change', function() {
            const price = $(this).find('option:selected').data('price');
            $(this).closest('.box-product-row').find('.box-price-input').val(price);
        });
        
        boxProductIndex++;
    }
    
    function removeBoxProduct(btn) {
        $(btn).closest('.box-product-row').remove();
    }
    
    // Auto-fill price when product is selected (for existing rows)
    $(document).on('change', '.box-product-select', function() {
        const price = $(this).find('option:selected').data('price');
        $(this).closest('.box-product-row').find('.box-price-input').val(price);
    });
    
    // Initialize select2 for existing box product selects
    $('.box-product-select').select2({
        dropdownParent: $('#AjaxModal')
    });
</script>