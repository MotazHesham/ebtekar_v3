<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="AjaxModalLabel">{{ __('global.extra.edit_product') }}</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ route('admin.receipt-clients.edit_product') }}" method="POST" enctype="multipart/form-data">
            @csrf   
            <input type="hidden" name="receipt_product_pivot_id" value="{{ $receipt_client_product_pivot->id }}">

            @php
                $isCustom = empty($receipt_client_product_pivot->receipt_client_product_id);
            @endphp

            <div class="form-group">
                <label class="d-block mb-2">طريقة الإضافة</label>
                <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                    <label class="btn btn-outline-primary {{ $isCustom ? '' : 'active' }} w-50">
                        <input type="radio" name="add_type" value="existing" {{ $isCustom ? '' : 'checked' }}> اختيار من المنتجات
                    </label>
                    <label class="btn btn-outline-primary {{ $isCustom ? 'active' : '' }} w-50">
                        <input type="radio" name="add_type" value="custom" {{ $isCustom ? 'checked' : '' }}> إضافة منتج جديد
                    </label>
                </div>
            </div>

            <div class="row" id="existing_product_section">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="product_id">{{ __('global.extra.product') }}</label>
                        <select class="form-control select2 mb-2" name="product_id" id="product_id">
                            <option value="">أختر المنتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    data-price="{{ $product->price }}"
                                    data-name="{{ $product->name }}"
                                    @if($receipt_client_product_pivot->receipt_client_product_id == $product->id) selected @endif>
                                    {{ $product->name }} - {{ $product->price }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="description">تفاصيل المنتج</label>
                        <textarea class="form-control" name="description" id="description" rows="3" required placeholder="تفاصيل المنتج">{{ $receipt_client_product_pivot->description }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="price">{{ __('global.extra.price') }}</label>
                        <input class="form-control" type="number" name="price" id="price" value="{{ $receipt_client_product_pivot->price }}" step="0.01" min="0" required placeholder="{{ __('global.extra.price') }}">
                    </div>
                    <div class="form-group">
                        <label for="quantity">{{ __('global.extra.quantity') }}</label>
                        <input class="form-control" type="number" name="quantity" id="quantity" value="{{ $receipt_client_product_pivot->quantity }}" step="1" min="1" required placeholder="{{ __('global.extra.quantity') }}">
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

<script> 
    $('#product_id').select2({
        dropdownParent: $('#AjaxModal2')
    });

    function toggleReceiptClientAddType() {
        var type = $('input[name="add_type"]:checked').val();
        if (type === 'custom') {
            $('#existing_product_section').hide();
            $('#product_id').prop('required', false);
        } else {
            $('#existing_product_section').show();
            $('#product_id').prop('required', true);
        }
    }

    $('input[name="add_type"]').on('change', toggleReceiptClientAddType);
    toggleReceiptClientAddType();

    $('#product_id').on('change', function() {
        var option = $(this).find('option:selected');
        if (option.val()) {
            $('#description').val(option.data('name') || '');
            $('#price').val(option.data('price') || '');
        }
    });
</script>
