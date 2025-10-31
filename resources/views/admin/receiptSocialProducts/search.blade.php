<div class="card">
    <div class="card-body">  
        <form action="" method="GET" id="sort_products"> 
            <div class="row">   
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 @isset($search) isset @endisset" id="search" name="search"
                        @isset($search) value="{{ $search }}" @endisset placeholder="اسم المنتج أو السعر"> 
                </div>  
                <div class="col-md-3">
                    <select class="form-control @isset($quantity) isset @endisset" name="quantity" id="quantity" >
                        <option value="">أختر الكمية</option>
                        <option value="1">1</option> 
                        <option value="more_than_1">أكثر من 1</option> 
                        <option value="2">2</option> 
                        <option value="3">3</option> 
                        <option value="4">4</option> 
                        <option value="5">5</option> 
                        <option value="6">6</option> 
                        <option value="7">7</option> 
                        <option value="8">8</option> 
                        <option value="9">9</option> 
                        <option value="10">10</option>  
                    </select>
                </div> 
                <div class="col-md-3">
                    <input type="text" class="form-control date mb-2  @isset($from_date) isset @endisset"
                        @isset($from_date) value="{{ request('from_date') }}" @endisset
                        name="from_date" id="from_date" placeholder="{{ __('global.extra.from_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control date mb-2  @isset($to_date) isset @endisset"
                        @isset($to_date) value="{{ request('to_date') }}" @endisset
                        name="to_date" id="to_date" placeholder="{{ __('global.extra.to_date') }}">
                </div>
                <div class="col-md-4">
                    <select class="form-control @isset($website_setting_id) isset @endisset" style="width: 200px" name="website_setting_id" id="website_setting_id" >
                        <option value="">أختر الموقع</option>
                        @foreach (\App\Models\WebsiteSetting::pluck('site_name', 'id') as $id => $entry)
                            <option value="{{ $id }}" >
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div> 
                <div class="col-md-4">
                    <input type="submit" value="{{ __('global.download') }}" name="download" class="btn btn-info btn-rounded btn-block">
                </div> 
            </div> 
        </form> 
    </div>
</div>
