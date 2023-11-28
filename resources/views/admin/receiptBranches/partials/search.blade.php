<div class="card">
    <div class="card-body"> 
        <form action="" method="GET" id="sort_receipt_branch">

            <div style="display: flex;justify-content: space-between;">
                <div>
                    <b>{{ trans('global.search') }} {{ trans('cruds.receiptBranch.title') }}</b>
                </div>
                <select class="form-control @isset($website_setting_id) isset @endisset" style="width: 200px" name="website_setting_id" id="website_setting_id" onchange="sort_receipt_branch()">
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
                                @isset($client_name) value="{{ $client_name }}" @endisset placeholder="{{ trans('cruds.receiptBranch.fields.client_name') }}">  
                                
                            <input type="text" class="form-control mb-2 @isset($order_num) isset @endisset" id="order_num" name="order_num" 
                                @isset($order_num) value="{{ $order_num }}" @endisset placeholder="{{ trans('cruds.receiptBranch.fields.order_num') }}">  
                                    
                            <select class="form-control mb-2 @isset($staff_id) isset @endisset" name="staff_id" id="staff_id" onchange="sort_receipt_branch()">
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
                                @isset($phone) value="{{ $phone }}" @endisset placeholder="{{ trans('cruds.receiptBranch.fields.phone_number') }}">

                            <input type="text" class="form-control mb-2 @isset($description) isset @endisset" id="description" name="description" 
                                @isset($description) value="{{ $description }}" @endisset placeholder="{{ trans('global.extra.description') }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control mb-2 @isset($done) isset @endisset" name="done" id="done" onchange="sort_receipt_branch()">
                                        <option value="">{{ trans('cruds.receiptBranch.fields.done') }}</option>
                                        <option value="1" @isset($done) @if ($done == '1') selected @endif @endisset>
                                            تم التسليم
                                        </option>
                                        <option value="0" @isset($done) @if ($done == '0') selected @endif @endisset>
                                            لم يتم التسليم
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control mb-2 @isset($quickly) isset @endisset" name="quickly" id="quickly" onchange="sort_receipt_branch()">
                                        <option value="">{{ trans('global.extra.quickly') }}</option>
                                        <option value="0" @isset($quickly) @if ($quickly == '0') selected @endif @endisset>
                                            {{ trans('global.extra.0_quickly') }}</option>
                                        <option value="1" @isset($quickly) @if ($quickly == '1') selected @endif @endisset>
                                            {{ trans('global.extra.1_quickly') }}</option>
                                    </select>  
                                </div>
                            </div>
                        </div>  
                    </div>  
                    <div class="row"> 
                        <div class="col-md-4">
                            <select class="form-control mb-2  @isset($date_type) isset @endisset" name="date_type" id="date_type">
                                <option value="">{{ trans('global.extra.date_type') }}</option>
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
                                name="from_date" id="from_date" placeholder="{{ trans('global.extra.from_date') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control date mb-2  @isset($to_date) isset @endisset"
                                @isset($to_date) value="{{ request('to_date') }}" @endisset
                                name="to_date" id="to_date" placeholder="{{ trans('global.extra.to_date') }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4  @isset($r_client_id) isset @endisset"> 
                            <select class="form-control select2" style="width: 200px" name="r_client_id" id="r_client_id" onchange="sort_receipt_branch()">
                                <option value="">أختر الأدراة</option>
                                @foreach ($rClients as $id => $entry)
                                    <option value="{{ $id }}" @isset($r_client_id) @if ($r_client_id == $id) selected @endif @endisset>
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select> 
                        </div>
                    
                        <div class="col-md-4  @isset($r_branch_id) isset @endisset">
                            <select class="form-control  select2" style="width: 200px" name="r_branch_id" id="r_branch_id" onchange="sort_receipt_branch()">
                                <option value="">أختر الفرع</option>
                                @foreach ($rBranches as $id => $entry)
                                    <option value="{{ $id }}" @isset($r_branch_id) @if ($r_branch_id == $id) selected @endif @endisset>
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select> 
                        </div>
                        <div class="col-md-4">
                            <select class="form-control mb-2 @isset($permission_status) isset @endisset" name="permission_status" id="permission_status" onchange="sort_receipt_branch()">
                                <option value="">حالة الأذن</option> 
                                @foreach(\App\Models\ReceiptBranch::PERMISSION_STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" @isset($permission_status) @if ($permission_status == $key) selected @endif @endisset>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3"></div> 
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
                @if(Gate::allows('download_receipts'))
                    <div class="col-md-2">
                        <input type="submit" value="{{ trans('global.download') }}" name="download" class="btn btn-info btn-rounded btn-block">
                    </div>
                @endif
                @can('receipt_branch_print')
                    <div class="col-md-2">
                        <input type="submit" value="{{ trans('global.print') }}" name="print" class="btn btn-danger btn-rounded btn-block">
                    </div>
                @endcan
                <div class="col-md-2">
                    <a class="btn btn-warning btn-rounded btn-block"  href="{{ route('admin.receipt-branches.index') }}">{{ trans('global.reset') }}</a>
                </div>
            </div> 
        </form>

    </div>
</div>
