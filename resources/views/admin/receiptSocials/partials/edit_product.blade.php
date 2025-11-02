<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="AjaxModalLabel">{{ __('global.extra.edit_product') }}</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ route('admin.receipt-socials.edit_product') }}" method="POST" enctype="multipart/form-data">
            @csrf   
            <input type="hidden" name="receipt_product_pivot_id" value="{{ $receipt_social_product_pivot->id }}">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="type">النوع</label>
                        <select class="form-control" name="type" id="product_type_select" required>
                            <option value="single" @if($receipt_social_product_pivot->type == 'single' || !$receipt_social_product_pivot->type) selected @endif>فردي</option>
                            <option value="box" @if($receipt_social_product_pivot->type == 'box') selected @endif>بوكس</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Single Product Section -->
            <div id="single_product_section" style="display: {{ $receipt_social_product_pivot->type == 'box' ? 'none' : 'block' }};">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">{{ __('global.extra.product') }}</label> 
                            <select class="form-control select2 mb-2" name="product_id" id="product_id"  >
                                <option value="">أختر المنتج</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" @if($receipt_social_product_pivot->receipt_social_product_id == $product->id) selected @endif>
                                        {{ $product->name }} - {{ $product->price }}
                                    </option>
                                @endforeach
                            </select>   
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">{{ __('global.extra.quantity') }}</label>
                            <input class="form-control" type="number" name="quantity" value="{{ $receipt_social_product_pivot->quantity }}" step="1" min="1" placeholder="{{ __('global.extra.quantity') }}"  title="{{ __('global.extra.quantity') }}" required>
                        </div>
                    </div>
                    
                    @if(auth()->user()->is_admin)
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">{{ __('cruds.receiptSocial.fields.extra_commission') }}</label>
                                <input class="form-control" type="number" name="extra_commission" value="{{ $receipt_social_product_pivot->extra_commission }}" step="0.1" min="0" placeholder="{{ __('cruds.receiptSocial.fields.extra_commission') }}" title="{{ __('cruds.receiptSocial.fields.extra_commission') }}" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">السعر</label>
                                <input class="form-control" type="number" name="price" value="{{ $receipt_social_product_pivot->price }}" step="0.1" min="0"  >
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Box Product Section -->
            <div id="box_product_section" style="display: {{ $receipt_social_product_pivot->type == 'box' ? 'block' : 'none' }};">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="box_title">عنوان البوكس (اختياري)</label>
                            <input class="form-control" type="text" name="box_title" value="{{ $receipt_social_product_pivot->title }}" placeholder="عنوان البوكس">
                        </div>
                    </div> 
                    @if(auth()->user()->is_admin)
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">السعر</label>
                                <input class="form-control" type="number" name="price" value="{{ $receipt_social_product_pivot->price }}" step="0.1" min="0"  >
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('cruds.receiptSocial.fields.extra_commission') }}</label>
                                <input class="form-control" type="number" name="extra_commission" value="{{ $receipt_social_product_pivot->extra_commission }}" step="0.1" min="0" placeholder="{{ __('cruds.receiptSocial.fields.extra_commission') }}" title="{{ __('cruds.receiptSocial.fields.extra_commission') }}" >
                            </div>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>منتجات البوكس</label>
                    <div id="box_products_container">
                        @php
                            $boxIndex = 0;
                        @endphp
                        @if($receipt_social_product_pivot->type == 'box' && $receipt_social_product_pivot->boxDetails && $receipt_social_product_pivot->boxDetails->count() > 0)
                            @foreach($receipt_social_product_pivot->boxDetails as $boxDetail)
                                <div class="box-product-row mb-3 p-3 border">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>المنتج</label>
                                                <select class="form-control select2 box-product-select" name="box_products[{{ $boxIndex }}][product_id]" required>
                                                    <option value="">أختر المنتج</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" data-price="{{ $product->box_price }}" @if($boxDetail->receipt_social_product_id == $product->id) selected @endif>
                                                            {{ $product->name }} - {{ $product->box_price }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>الكمية</label>
                                                <input class="form-control" type="number" name="box_products[{{ $boxIndex }}][quantity]" step="1" min="1" value="{{ $boxDetail->quantity }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>السعر</label>
                                                <input class="form-control box-price-input" type="number" name="box_products[{{ $boxIndex }}][price]" step="0.01" min="0" value="{{ $boxDetail->price }}" required>
                                            </div>
                                        </div>
                                        @php
                                            $boxIndex++;
                                        @endphp
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-block remove-box-product" onclick="removeBoxProduct(this)">حذف</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="box-product-row mb-3 p-3 border">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>المنتج</label>
                                            <select class="form-control select2 box-product-select" name="box_products[0][product_id]" required>
                                                <option value="">أختر المنتج</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->name }} - {{ $product->price }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>الكمية</label>
                                            <input class="form-control" type="number" name="box_products[0][quantity]" step="1" min="1" value="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>السعر</label>
                                            <input class="form-control box-price-input" type="number" name="box_products[0][price]" step="0.01" min="0" required>
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
                        @endif
                    </div>
                    <button type="button" class="btn btn-info" onclick="addBoxProduct()">إضافة منتج للبوكس</button>
                </div>
            </div>
            <div class="form-group">
                <textarea class="form-control ckeditor" name="description" placeholder="{{ __('global.extra.description') }}"  cols="30" rows="6">{{ $receipt_social_product_pivot->description }}</textarea>
            </div>
            <div class="form-group"> 
                <label> 
                    <span>PDF</span> 
                    @if($receipt_social_product_pivot->pdf)
                        @foreach (json_decode($receipt_social_product_pivot->pdf) as $pdf)
                            <div class="d-flex align-items-center mb-2">
                                <a href="{{ asset($pdf) }}" target="_blanc" class="btn btn-info mr-2">show pdf {{ $loop->iteration }}</a>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_pdfs[]" value="{{ $pdf }}" id="remove-pdf-{{ $loop->iteration }}">
                                    <label class="form-check-label" for="remove-pdf-{{ $loop->iteration }}">{{ __('global.delete') }}</label>
                                </div>
                            </div>
                        @endforeach
                    @endif 
                </label>
                <input type="file" class="form-control" name="pdf[]" multiple>
            </div>
            <div class="form-group"> 
                <div id="product-images">
                    <label>{{ __('cruds.receiptSocialProduct.fields.photos') }}</label>
                    @if($receipt_social_product_pivot->photos)
                        @foreach (json_decode($receipt_social_product_pivot->photos) as $key => $photo)
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" onclick="delete_this_row(this)" class="btn btn-danger">{{ trans("global.extra.delete_photo") }}</button>
                                </div>
                                <input type="hidden" name="previous_photos[{{$key}}][photo]"  value="{{$photo->photo}}"> 
                                <div class="col-md-2">
                                    <a href="{{asset($photo->photo)}}" target="_blanc">
                                        <img src="{{asset($photo->photo)}}" width="50" height="50" alt="">
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" value="{{ $photo->note ?? ''}}" name="previous_photos[{{$key}}][note]" class="form-control" placeholder="{{ __('global.extra.photo_note') }}">
                                </div>
                            </div>
                        @endforeach
                    @else
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
                    @endif
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-info mb-3" onclick="add_more_slider_image()">{{ __('global.extra.add_more') }}</button>
                </div> 
            </div>
            <hr>
            
            <div class="form-group"> 
                <button type="submit" class="btn btn-dark btn-block">{{ __('global.save') }}</button>
            </div>
        </form>
    </div> 
</div>

<script> 
    $('#product_id').select2({
        dropdownParent: $('#AjaxModal2')
    });
    
    var boxProductIndex = {{ ($receipt_social_product_pivot->type == 'box' && $receipt_social_product_pivot->boxDetails) ? $receipt_social_product_pivot->boxDetails->count() : 0 }};
    
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
            dropdownParent: $('#AjaxModal2')
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
        dropdownParent: $('#AjaxModal2')
    });
</script>