<div class="card">
    <div class="card-header">
        {{ trans('cruds.receiptSocial.fields.delivery_man_id') }}
    </div>
    <div class="card-body">

        @if ($site_settings->delivery_system == 'ebtekar')
            <form action="{{ route($crudRoutePart.'update_delivery_man') }}"method="POST">
                @csrf

                <input type="hidden" name="row_id" value="{{ $row->id }}" id="">

                <div class="form-group"> 
                    <div class="col-md-6">
                        <select class="form-control select2" name="delivery_man_id" id="delivery_man_id" required>
                            <option value="">{{ trans('global.pleaseSelect') }}</option>
                            @foreach (\App\Models\User::where('user_type', 'delivery_man')->get() as $delivery_man)
                                <option value="{{ $delivery_man->id }}"
                                    @if ($row->delivery_man_id == $delivery_man->id) selected @endif>
                                    {{ $delivery_man->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-info btn-rounded">{{ trans('global.update') }}</button>
            </form>
        @elseif($site_settings->delivery_system == 'wasla')
            @if ($response['data'] ?? null)
                @if (!$row->send_to_wasla_date)
                    <form action="{{ route($crudRoutePart.'send_to_wasla') }}" method="POST">
                        @csrf

                        <input type="hidden" name="row_id" value="{{ $row->id }}" id="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required" for="country_id">{{ __('Countries') }}</label>
                                    @if ($response['data'] ?? null)
                                        <select class="form-control select2" name="country_id" id="country_id" required>
                                            @foreach ($response['data'] as $country)
                                                <option value="{{ $country['id'] }}">
                                                    {{ dashboard_currency($country['cost']) }} -
                                                    {{ $country['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        not found
                                    @endif

                                </div>
                                <div class="form-group">
                                    <label class="required" for="type">هل سيستلم الكابتن طرد من العميل
                                        ؟</label>
                                    <select class="form-control" name="type" id="type" required>
                                        <option value="no">لا</option>
                                        <option value="partial">مرتجع جزئي</option>
                                        <option value="change">مرتجع استبدال</option>
                                        <option value="return">مرتجع استرجاع</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required" for="status">الحالة</label>
                                    <select class="form-control" name="status" id="status" required>
                                        <option value="draft">في المحفوطة</option>
                                        <option value="sent">مرسلة للشحن</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="required" for="district">المنطقة</label>
                                    <input type="text" name="district" class="form-control" id="district" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required" for="cost">المطلوب تحصيله شامل
                                        الشحن</label>
                                    <input type="number" name="cost" class="form-control" id="cost"
                                        value="{{ $row->calc_total_for_client() }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="required" for="in_return_case">في حالة الأسترجاع</label>
                                    <input type="number" name="in_return_case" class="form-control" id="in_return_case"
                                        required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger btn-rounded">أرسال لوصلة</button>
                    </form>
                @else
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="text-center" style="padding:20px">
                                <i class="fa fa-check-circle" style="font-size: 40px; color: green;padding:10px"></i>
                                <br>
                                تم أرسال الفاتورة لوصلة
                            </h4>
                        </div>
                        <div class="col-md-6" style="padding:45px">
                            <div>
                                <span
                                    class="badge text-bg-{{ trans('global.delivery_status.colors.' . $row->delivery_status) }}">
                                    {{ $row->delivery_status ? trans('global.delivery_status.status.' . $row->delivery_status) : '' }}
                                </span>
                                @if ($row->delivery_status == 'delay')
                                    <span class="badge badge-warning">{{ $row->delay_reason }}</span>
                                @elseif($row->delivery_status == 'delivered')
                                    <span class="badge badge-success">{{ $row->done_time }}</span>
                                @elseif($row->delivery_status == 'cancel')
                                    <span class="badge badge-danger">{{ $row->cancel_reason }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <a href="{{ route('profile.password.edit') }}" class="btn btn-dark">
                    Try login to Wasla again
                </a>
            @endif
        @endif
    </div>
</div>
