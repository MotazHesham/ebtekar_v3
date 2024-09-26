
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="AjaxModalLabel">{{ __('global.extra.add_product') }}</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ route('admin.receipt-outgoings.add_product') }}" method="POST" enctype="multipart/form-data">
            @csrf  
            <input type="hidden" name="receipt_id" value="{{ $receipt_id }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">{{ __('global.extra.product') }}</label> 
                        <input class="form-control" type="text" name="description" step="1" min="1" required placeholder="{{ __('global.extra.description') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">{{ __('global.extra.price') }}</label>
                        <input class="form-control" type="number" name="price" step="1" min="1" required placeholder="{{ __('global.extra.price') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">{{ __('global.extra.quantity') }}</label>
                        <input class="form-control" type="number" name="quantity" step="1" min="1" required placeholder="{{ __('global.extra.quantity') }}" required>
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