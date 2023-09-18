<div class="card">
    <div class="card-body">  
        <form action="" method="GET" id="sort_products"> 
            <div class="row">   
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 @isset($search) isset @endisset" id="search" name="search"
                        @isset($search) value="{{ $search }}" @endisset placeholder="اسم المنتج أو السعر"> 
                </div>  
                <div class="col-md-3">
                    <input type="text" class="form-control date mb-2  @isset($from_date) isset @endisset"
                        @isset($from_date) value="{{ request('from_date') }}" @endisset
                        name="from_date" id="from_date" placeholder="{{ trans('global.extra.from_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control date mb-2  @isset($to_date) isset @endisset"
                        @isset($to_date) value="{{ request('to_date') }}" @endisset
                        name="to_date" id="to_date" placeholder="{{ trans('global.extra.to_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="submit" value="{{ trans('global.download') }}" name="download" class="btn btn-info btn-rounded btn-block">
                </div> 
            </div> 
        </form> 
    </div>
</div>
