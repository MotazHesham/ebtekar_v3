<div class="card">
    <div class="card-body"> 
        <b>{{ __('global.search') }} {{ __('cruds.receiptCompany.title') }}</b>
        <hr>
        <form action="" method="GET" id="sort_receipt_company">
            @isset($deleted)
                <input type="hidden" name="deleted" value="1">
            @endisset
            <div class="row">

                <div class="col-md-3"> 
                    <input type="text" class="form-control mb-2 @isset($client_name) isset @endisset" id="client_name" name="client_name"
                        @isset($client_name) value="{{ $client_name }}" @endisset placeholder="{{ __('cruds.receiptCompany.fields.client_name') }}">  
                        
                    <input type="text" class="form-control mb-2 @isset($order_num) isset @endisset" id="order_num" name="order_num" 
                        @isset($order_num) value="{{ $order_num }}" @endisset placeholder="{{ __('cruds.receiptCompany.fields.order_num') }}">  
                            
                    <select class="form-control mb-2 @isset($staff_id) isset @endisset" name="staff_id" id="staff_id" onchange="sort_receipt_company()">
                        <option value="">أختر الموظف</option>
                        @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}" @isset($staff_id) @if ($staff_id == $staff->id) selected @endif @endisset>
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="row">
                        <div class="col-md-12">
                            <select class="form-control mb-2 @isset($done) isset @endisset" name="done" id="done" onchange="sort_receipt_company()">
                                <option value="">{{ __('cruds.receiptCompany.fields.done') }}</option>
                                <option value="1" @isset($done) @if ($done == '1') selected @endif @endisset>
                                    تم التسليم
                                </option>
                                <option value="0" @isset($done) @if ($done == '0') selected @endif @endisset>
                                    لم يتم التسليم
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control mb-2 @isset($calling) isset @endisset" name="calling" id="calling" onchange="sort_receipt_company()" >
                                <option value="">{{ __('cruds.receiptCompany.fields.calling') }}</option>
                                <option value="0"
                                    @isset($calling) @if ($calling == '0') selected @endif @endisset>
                                    لم يتم الأتصال</option>
                                <option value="1"
                                    @isset($calling) @if ($calling == '1') selected @endif @endisset>
                                    تم الأتصال</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control mb-2 @isset($no_answer) isset @endisset" name="no_answer" id="no_answer" onchange="sort_receipt_company()">
                                <option value="">{{ __('global.extra.no_answer') }}</option>
                                <option value="1" @isset($no_answer) @if ($no_answer == '1') selected @endif @endisset> لم يتم الرد</option> 
                            </select> 
                        </div>
                    </div>
                </div> 
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 @isset($phone) isset @endisset" id="phone" name="phone"
                        @isset($phone) value="{{ $phone }}" @endisset placeholder="{{ __('cruds.receiptCompany.fields.phone_number') }}">

                    <input type="text" class="form-control mb-2 @isset($description) isset @endisset" id="description" name="description" 
                        @isset($description) value="{{ $description }}" @endisset placeholder="{{ __('global.extra.description') }}">

                    <select class="form-control mb-2 @isset($delivery_man_id) isset @endisset" name="delivery_man_id" id="delivery_man_id" onchange="sort_receipt_company()">
                        <option value="">{{ __('cruds.receiptCompany.fields.delivery_man_id') }}</option>
                        @foreach ($delivery_mans as $raw)
                            <option value="{{ $raw->id }}" @isset($delivery_man_id) @if ($delivery_man_id == $raw->id) selected @endif @endisset>
                                {{ $raw->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="mb-3 @isset($country_id) isset @endisset">
                        <select class="form-control select2" name="country_id" id="country_id" onchange="sort_receipt_social()">
                            <option value="">{{ __('cruds.receiptSocial.fields.shipping_country_id') }}</option>
                            @if(isset($countries['districts']))
                                <optgroup label="{{ __('Districts') }}">
                                    @foreach ($countries['districts'] as $district)
                                        <option value={{ $district->id }} @if ($district->id == $country_id) selected @endif>
                                            {{ $district->name }} -  {{ dashboard_currency($district->cost) }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if(isset($countries['countries']))
                                <optgroup label="{{ __('Countries') }}">
                                    @foreach ($countries['countries'] as $country)
                                        <option value={{ $country->id }}
                                            @if ($country->id == $country_id) selected @endif>
                                            {{ $country->name }} -  {{ dashboard_currency($country->cost) }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if(isset($countries['metro']))
                                <optgroup label="{{ __('Metro') }}">
                                    @foreach ($countries['metro'] as $raw)
                                        <option value={{ $raw->id }}
                                            @if ($raw->id == $country_id) selected @endif>
                                            {{ $raw->name }} -  {{ dashboard_currency($raw->cost) }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div> 
                        
                    <select class="form-control mb-2 @isset($sent_to_delivery) isset @endisset" name="sent_to_delivery" id="sent_to_delivery" onchange="sort_receipt_company()" >
                        <option value="">{{ __('global.extra.sent_to_delivery') }}</option>
                        <option value="0"
                            @isset($sent_to_delivery) @if ($sent_to_delivery == '0') selected @endif @endisset>
                            لم يتم الأرسال</option>
                        <option value="1"
                            @isset($sent_to_delivery) @if ($sent_to_delivery == '1') selected @endif @endisset>
                            تم الأرسال</option>
                    </select> 

                </div> 
                <div class="col-md-3">  
                    
                    <select class="form-control mb-2 @isset($client_type) isset @endisset" name="client_type" id="client_type" onchange="sort_receipt_company()">
                        <option value="">{{ __('cruds.receiptCompany.fields.client_type') }}</option>
                        <option value="individual"
                            @isset($client_type) @if ($client_type == 'individual') selected @endif @endisset>
                            Individual</option>
                        <option value="corporate"
                            @isset($client_type) @if ($client_type == 'corporate') selected @endif @endisset>
                            Corporate</option>
                    </select>
                    <select class="form-control mb-2 @isset($quickly) isset @endisset" name="quickly" id="quickly" onchange="sort_receipt_company()">
                        <option value="">{{ __('global.extra.quickly') }}</option>
                        <option value="0" @isset($quickly) @if ($quickly == '0') selected @endif @endisset>
                            {{ __('global.extra.0_quickly') }}</option>
                        <option value="1" @isset($quickly) @if ($quickly == '1') selected @endif @endisset>
                            {{ __('global.extra.1_quickly') }}</option>
                    </select>  

                    <select class="form-control mb-2 @isset($delivery_status) isset @endisset" name="delivery_status" id="delivery_status" onchange="sort_receipt_company()">
                        <option value="">{{ __('cruds.receiptCompany.fields.delivery_status') }}</option> 
                        @foreach(__('global.delivery_status.status') as $key => $status)
                            <option value="{{ $key }}" @isset($delivery_status) @if ($delivery_status == $key) selected @endif @endisset>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select> 
                    <select class="form-control mb-2 @isset($payment_status) isset @endisset" name="payment_status" id="payment_status" onchange="sort_receipt_company()">
                        <option value="">{{ __('cruds.receiptCompany.fields.payment_status') }}</option> 
                        @foreach(__('global.payment_status.status') as $key => $status)
                            <option value="{{ $key }}" @isset($payment_status) @if ($payment_status == $key) selected @endif @endisset>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>


                    <select class="form-control mb-2 @isset($playlist_status) isset @endisset" name="playlist_status"id="playlist_status" onchange="sort_receipt_company()">
                        <option value="">{{ __('cruds.receiptCompany.fields.playlist_status') }}</option>
                        @foreach(__('global.playlist_status.status') as $key => $status)
                            <option value="{{ $key }}" @isset($playlist_status) @if ($playlist_status == $key) selected @endif @endisset>
                                {{ $status }}
                            </option> 
                        @endforeach
                    </select>

                    
                </div> 
                <div class="col-md-3">

                    <div class="row">
                        <div class="col-md-6">
                            {{-- from receipt --}}
                            <div class=" text-center">
                                <span class="badge badge-light text-dark">{{ __('global.extra.from_order') }}</span>
                                <input type="text" class="form-control @isset($from) isset @endisset" name="from" placeholder="  رقم أوردر"
                                    @isset($from) value="{{ $from }}" @endisset>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- to receipt --}}
                            <div class=" text-center">
                                <span class="badge badge-light text-dark">{{ __('global.extra.to_order') }}</span>
                                <input type="text" class="form-control @isset($to) isset @endisset" name="to" placeholder="  رقم أوردر"
                                    @isset($to) value="{{ $to }}" @endisset>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            {{-- exclude receipts --}}
                            <div class=" text-center" style="min-width: 160px; margin-bottom: 10px">
                                <span class="badge badge-light text-dark">{{ __('global.extra.exclude_orders') }}</span>
                                <input type="text" class="form-control @isset($exclude) isset @endisset" name="exclude" placeholder="أضافة رقم أوردر" data-role="tagsinput" @isset($exclude)  value="{{ $exclude }}" @endisset>
                            </div>
                        </div>
                        <div class="col-md-12"> 
                            
                            {{-- include receipts --}}
                            <div class=" text-center" style="min-width: 160px; margin-bottom: 10px">
                                <span class="badge badge-light text-dark">{{ __('global.extra.include_orders') }}</span>
                                <input type="text" class="form-control @isset($include) isset @endisset" name="include" placeholder="أضافة رقم أوردر" data-role="tagsinput" @isset($include)  value="{{ $include }}" @endisset>
                            </div>
                        </div>
                    </div> 

                </div>
            </div>
                
            <div class="row"> 
                <div class="col-md-4">
                    <select class="form-control mb-2  @isset($date_type) isset @endisset" name="date_type" id="date_type">
                        <option value="">{{ __('global.extra.date_type') }}</option>
                        <option value="created_at"
                            @isset($date_type) @if ($date_type == 'created_at') selected @endif @endisset>
                            {{ __('تاريخ الأضافة') }}</option>
                        <option value="send_to_playlist_date"
                            @isset($date_type) @if ($date_type == 'send_to_playlist_date') selected @endif @endisset>
                            {{ __('تاريخ المرحلة') }}</option>
                        <option value="date_of_receiving_order"
                            @isset($date_type) @if ($date_type == 'date_of_receiving_order') selected @endif @endisset>
                            {{ __('تاريخ استلام الطلب') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control date mb-2  @isset($from_date) isset @endisset"
                        @isset($from_date) value="{{ request('from_date') }}" @endisset
                        name="from_date" id="from_date" placeholder="{{ __('global.extra.from_date') }}">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control date mb-2  @isset($to_date) isset @endisset"
                        @isset($to_date) value="{{ request('to_date') }}" @endisset
                        name="to_date" id="to_date" placeholder="{{ __('global.extra.to_date') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <input type="submit" value="{{ __('global.search') }}" name="search" class="btn btn-success btn-rounded btn-block">
                </div>
                @if(Gate::allows('download_receipts'))
                    <div class="col-md-2">
                        <input type="submit" value="{{ __('global.download') }}" name="download" class="btn btn-info btn-rounded btn-block">
                    </div>
                @endif
                
                @can('receipt_company_print')
                    <div class="col-md-2">
                        <input type="submit" value="{{ __('global.print') }}" name="print" class="btn btn-danger btn-rounded btn-block">
                    </div>
                @endcan
                
                <div class="col-md-2">
                    <a class="btn btn-warning btn-rounded btn-block"  href="{{ route('admin.receipt-companies.index') }}">{{ __('global.reset') }}</a>
                </div> 
            </div> 
        </form>

    </div>
</div>
