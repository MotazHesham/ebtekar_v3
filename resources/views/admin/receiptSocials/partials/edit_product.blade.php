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
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">{{ __('global.extra.product') }}</label> 
                        <select class="form-control select2 mb-2" name="product_id" id="product_id" required>
                            <option value="">أختر المنتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @if($receipt_social_product_pivot->receipt_social_product_id == $product->id) selected @endif>
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
            <div class="form-group">
                <textarea class="form-control ckeditor" name="description" placeholder="{{ __('global.extra.description') }}"  cols="30" rows="6">{{ $receipt_social_product_pivot->description }}</textarea>
            </div>
            <div class="form-group"> 
                <label> 
                    <span>PDF</span> 
                    @if($receipt_social_product_pivot->pdf)
                        <a href="{{ asset($receipt_social_product_pivot->pdf) }}" target="_blanc" class="btn btn-info">show pdf</a>
                    @endif 
                </label>
                <input type="file" class="form-control" name="pdf">
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
</script>