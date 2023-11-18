<div class="card">
    <div class="card-body">  
        <form action="" method="GET" id="sort_receipt_price_view">

            <div style="display: flex;justify-content: space-between;">
                <div>
                    <b>{{ trans('global.search') }} {{ trans('cruds.receiptPriceView.title') }}</b>
                </div>
                <select class="form-control @isset($website_setting_id) isset @endisset" style="width: 200px" name="website_setting_id" id="website_setting_id" onchange="sort_receipt_price_view()">
                    <option value="">أختر الموقع</option>
                    @foreach ($websites as $id => $entry)
                        <option value="{{ $id }}" @isset($website_setting_id) @if ($website_setting_id == $id) selected @endif @endisset>
                            {{ $entry }}
                        </option>
                    @endforeach
                </select>
            </div>
            <hr>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6"> 
                            <input type="text" class="form-control mb-2 @isset($client_name) isset @endisset" id="client_name" name="client_name"
                                @isset($client_name) value="{{ $client_name }}" @endisset placeholder="{{ trans('cruds.receiptPriceView.fields.client_name') }}">  
                                
                            <input type="text" class="form-control mb-2 @isset($order_num) isset @endisset" id="order_num" name="order_num" 
                                @isset($order_num) value="{{ $order_num }}" @endisset placeholder="{{ trans('cruds.receiptPriceView.fields.order_num') }}">  
                                    
                            <select class="form-control mb-2 @isset($staff_id) isset @endisset" name="staff_id" id="staff_id" onchange="sort_receipt_price_view()">
                                <option value="">أختر الموظف</option>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}" @isset($staff_id) @if ($staff_id == $staff->id) selected @endif @endisset>
                                        {{ $staff->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> 
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2 @isset($phone) isset @endisset" id="phone" name="phone"
                                @isset($phone) value="{{ $phone }}" @endisset placeholder="{{ trans('cruds.receiptPriceView.fields.phone_number') }}">

                            <input type="text" class="form-control mb-2 @isset($description) isset @endisset" id="description" name="description" 
                                @isset($description) value="{{ $description }}" @endisset placeholder="{{ trans('global.extra.description') }}"> 

                            <input type="text" class="form-control mb-2 @isset($place) isset @endisset" id="place" name="place" 
                                @isset($place) value="{{ $place }}" @endisset placeholder="{{ trans('cruds.receiptPriceView.fields.place') }}"> 
                        </div>  
                    </div>  
                    <div class="row"> 
                        <div class="col-md-4">
                            <select class="form-control mb-2  @isset($date_type) isset @endisset" name="date_type" id="date_type">
                                <option value="">{{ trans('global.extra.date_type') }}</option>
                                <option value="created_at"
                                    @isset($date_type) @if ($date_type == 'created_at') selected @endif @endisset>
                                    {{ __('تاريخ الأضافة') }}</option> 
                                <option value="date_of_receiving_order"
                                    @isset($date_type) @if ($date_type == 'date_of_receiving_order') selected @endif @endisset>
                                    {{ __('تاريخ استلام الطلب') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control date mb-2  @isset($from_date) isset @endisset"
                                @isset($from_date) value="{{ request('from_date') }}" @endisset
                                name="from_date" id="from_date" placeholder="{{ trans('global.extra.from_date') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control date mb-2  @isset($to_date) isset @endisset"
                                @isset($to_date) value="{{ request('to_date') }}" @endisset
                                name="to_date" id="to_date" placeholder="{{ trans('global.extra.to_date') }}">
                        </div>
                    </div>
                </div>  
                <div class="col-md-4">

                    <div class="row">
                        <div class="col-md-6">
                            {{-- from receipt --}}
                            <div class=" text-center">
                                <span class="badge badge-light text-dark">{{ trans('global.extra.from_order') }}</span>
                                <input type="text" class="form-control @isset($from) isset @endisset" name="from" placeholder="  رقم أوردر"
                                    @isset($from) value="{{ $from }}" @endisset>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- to receipt --}}
                            <div class=" text-center">
                                <span class="badge badge-light text-dark">{{ trans('global.extra.to_order') }}</span>
                                <input type="text" class="form-control @isset($to) isset @endisset" name="to" placeholder="  رقم أوردر"
                                    @isset($to) value="{{ $to }}" @endisset>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            {{-- exclude receipts --}}
                            <div class=" text-center" style="min-width: 160px; margin-bottom: 10px">
                                <span class="badge badge-light text-dark">{{ trans('global.extra.exclude_orders') }}</span>
                                <input type="text" class="form-control @isset($exclude) isset @endisset" name="exclude" placeholder="أضافة رقم أوردر" data-role="tagsinput" @isset($exclude)  value="{{ $exclude }}" @endisset>
                            </div>
                        </div>
                        <div class="col-md-12"> 
                            
                            {{-- include receipts --}}
                            <div class=" text-center" style="min-width: 160px; margin-bottom: 10px">
                                <span class="badge badge-light text-dark">{{ trans('global.extra.include_orders') }}</span>
                                <input type="text" class="form-control @isset($include) isset @endisset" name="include" placeholder="أضافة رقم أوردر" data-role="tagsinput" @isset($include)  value="{{ $include }}" @endisset>
                            </div>
                        </div>
                    </div> 

                </div>
            </div>
                

            <div class="row">
                <div class="col-md-3">
                    <input type="submit" value="{{ trans('global.search') }}" name="search" class="btn btn-success btn-rounded btn-block">
                </div>
                @if(auth()->user()->is_admin )
                    <div class="col-md-2">
                        <input type="submit" value="{{ trans('global.download') }}" name="download" class="btn btn-info btn-rounded btn-block">
                    </div>
                @endif
                @can('receipt_outgoing_print')
                    <div class="col-md-2">
                        <input type="submit" value="{{ trans('global.print') }}" name="print" class="btn btn-danger btn-rounded btn-block">
                    </div>
                @endcan
                <div class="col-md-2">
                    <a class="btn btn-warning btn-rounded btn-block"  href="{{ route('admin.receipt-price-views.index') }}">{{ trans('global.reset') }}</a>
                </div>
            </div> 
        </form>

    </div>
</div>
