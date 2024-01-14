<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">{{ $qr_product->product }} </h5> 
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form method="POST" action="{{ route('admin.qr-products.update') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $qr_product->id }}"> 
            <div class="row">  
                <div class="form-group col-md-6">
                    <label class="required"
                        for="keys">الأسماء</label>
                    <input class="form-control {{ $errors->has('keys') ? 'is-invalid' : '' }}" type="text"
                        name="keys" id="keys" placeholder="أضف اسم ..." data-role="tagsinput" required>
                    @if ($errors->has('keys'))
                        <div class="invalid-feedback">
                            {{ $errors->first('keys') }}
                        </div>
                    @endif 
                </div>
                <div class="form-group col-md-6">
                    <br> 
                    <button class="btn btn-success" type="submit" name="update_keys">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </div> 
        </form>
        <table class=" table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm datatable-names">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        id
                    </th>
                    <th>
                        name
                    </th> 
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($names as $name)
                    <tr data-entry-id="{{ $name->id }}">
                        <td>

                        </td>
                        <td>  
                            {{ $name->id ?? '' }}
                        </td>  
                        <td> 
                            {{ $name->name ?? '' }}
                        </td>  
                        <td>  
                            <a href="{{ route('admin.qr-products.print',$name->id) }}" target="_blanc" class="btn btn-warning">طباعة</a>
                            <a href="{{ route('admin.qr-products.delete_name',$name->id) }}" onclick="return confirm('are you sure?')" class="btn btn-danger">حذف</a>
                        </td> 
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            No data available in table
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table> 
    </div>
</div>

<script>
    $(function() {
        $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
    }); 
</script>  