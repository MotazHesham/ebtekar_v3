<form action="" method="GET" id="sort_receipt_social">
    @isset($deleted)
        <input type="hidden" name="deleted" value="1">
    @endisset
    <input type="hidden" name="new_design" value="{{ request('new_design') }}">
    <!-- Modern Floating Search Bar -->
    <div class="modern-search-container">
        <!-- Quick Search Bar -->
        <div class="floating-search-bar">
            <div class="search-input-group">
                <div class="search-icon">
                    <i class="fas fa-search text-dark"></i>
                </div>
                <input type="text" name="general_search" @isset($general_search) value="{{ $general_search }}" @endisset class="modern-search-input"
                    placeholder="ابحث في الفواتير..." id="globalSearch">
                <div class="search-actions">
                    <button class="btn btn-outline-dark btn-sm btn-rounded" type="button" style="border-radius: 10px;"
                        onclick="toggleAdvancedFilters()">
                        <i class="fas fa-filter me-1"></i>
                        فلاتر متقدمة
                    </button>
                    <button class="btn btn-success btn-sm btn-rounded" style="border-radius: 10px;"
                        onclick="submitSearch()">
                        <i class="fas fa-search me-1"></i>
                        بحث
                    </button>
                </div>
            </div>
        </div>

        <!-- Advanced Filters Panel -->
        <div class="advanced-filters-panel" id="advancedFiltersPanel">

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <button type="button" class="filter-tab active" data-tab="basic">
                    <i class="fas fa-info-circle me-2"></i>
                    أساسي
                </button>
                <button type="button" class="filter-tab" data-tab="status">
                    <i class="fas fa-tasks me-2"></i>
                    الحالات
                </button>
                <button type="button" class="filter-tab" data-tab="financial">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    مالي
                </button>
                <button type="button" class="filter-tab" data-tab="dates">
                    <i class="fas fa-calendar-alt me-2"></i>
                    التواريخ
                </button>
                <button type="button" class="filter-tab" data-tab="advanced">
                    <i class="fas fa-cogs me-2"></i>
                    متقدم
                </button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Basic Tab -->
                <div class="tab-pane active" id="basic-tab">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.order_num') }}</label>
                            <input type="text"
                                class="form-control @isset($order_num) isset @endisset" id="order_num"
                                name="order_num"
                                @isset($order_num) value="{{ $order_num }}" @endisset
                                placeholder="رقم الأوردر">
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.client_name') }}</label>
                            <input type="text"
                                class="form-control @isset($client_name) isset @endisset"
                                id="client_name" name="client_name"
                                @isset($client_name) value="{{ $client_name }}" @endisset
                                placeholder="اسم العميل">
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.phone_number') }}</label>
                            <input type="text"
                                class="form-control @isset($phone) isset @endisset" id="phone"
                                name="phone"
                                @isset($phone) value="{{ $phone }}" @endisset
                                placeholder="رقم الهاتف">
                        </div>
                        <div class="filter-item">
                            <label>نوع الطلب</label>
                            <select class="form-control @isset($isShopify) isset @endisset"
                                name="isShopify" id="isShopify">
                                <option value="">تحديد نوع الطلب</option>
                                <option value="1"
                                    @isset($isShopify) @if ($isShopify == '1') selected @endif @endisset>
                                    طلب شوبيفاي</option>
                                <option value="0"
                                    @isset($isShopify) @if ($isShopify == '0') selected @endif @endisset>
                                    طلب غير شوبيفاي</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>أختر الموقع</label>
                            <select class="form-control @isset($website_setting_id) isset @endisset"
                                name="website_setting_id" id="website_setting_id">
                                <option value="">أختر الموقع</option>
                                @foreach ($websites as $id => $entry)
                                    <option value="{{ $id }}"
                                        @isset($website_setting_id) @if ($website_setting_id == $id) selected @endif @endisset>
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>أختر الموظف</label>
                            <select class="form-control @isset($staff_id) isset @endisset"
                                name="staff_id" id="staff_id">
                                <option value="">أختر الموظف</option>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}"
                                        @isset($staff_id) @if ($staff_id == $staff->id) selected @endif @endisset>
                                        {{ $staff->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.client_type') }}</label>
                            <select class="form-control @isset($client_type) isset @endisset"
                                name="client_type" id="client_type">
                                <option value="">{{ __('cruds.receiptSocial.fields.client_type') }}</option>
                                <option value="individual"
                                    @isset($client_type) @if ($client_type == 'individual') selected @endif @endisset>
                                    فردي</option>
                                <option value="corporate"
                                    @isset($client_type) @if ($client_type == 'corporate') selected @endif @endisset>
                                    شركة</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('global.extra.quickly') }}</label>
                            <select class="form-control @isset($quickly) isset @endisset"
                                name="quickly" id="quickly">
                                <option value="">{{ __('global.extra.quickly') }}</option>
                                <option value="0"
                                    @isset($quickly) @if ($quickly == '0') selected @endif @endisset>
                                    {{ __('global.extra.0_quickly') }}</option>
                                <option value="1"
                                    @isset($quickly) @if ($quickly == '1') selected @endif @endisset>
                                    {{ __('global.extra.1_quickly') }}</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>نوع المنتج</label>
                            <select class="form-control @isset($product_type) isset @endisset"
                                name="product_type" id="product_type">
                                <option value="">نوع المنتج</option>
                                <option value="1"
                                    @isset($product_type) @if ($product_type == '1') selected @endif @endisset>
                                    منتج سيزون</option>
                                <option value="0"
                                    @isset($product_type) @if ($product_type == '0') selected @endif @endisset>
                                    منتج غير سيزون</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Status Tab -->
                <div class="tab-pane" id="status-tab">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label>{{ __('global.extra.sent_to_delivery') }}</label>
                            <select class="form-control @isset($sent_to_delivery) isset @endisset"
                                name="sent_to_delivery" id="sent_to_delivery">
                                <option value="">{{ __('global.extra.sent_to_delivery') }}</option>
                                <option value="0"
                                    @isset($sent_to_delivery) @if ($sent_to_delivery == '0') selected @endif @endisset>
                                    لم يتم الأرسال</option>
                                <option value="1"
                                    @isset($sent_to_delivery) @if ($sent_to_delivery == '1') selected @endif @endisset>
                                    تم الأرسال</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.confirm') }}</label>
                            <select class="form-control @isset($confirm) isset @endisset"
                                name="confirm" id="confirm">
                                <option value="">{{ __('cruds.receiptSocial.fields.confirm') }}</option>
                                <option value="0"
                                    @isset($confirm) @if ($confirm == '0') selected @endif @endisset>
                                    لم يتم التأكيد</option>
                                <option value="1"
                                    @isset($confirm) @if ($confirm == '1') selected @endif @endisset>
                                    تم التأكيد</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.done') }}</label>
                            <select class="form-control @isset($done) isset @endisset"
                                name="done" id="done">
                                <option value="">{{ __('cruds.receiptSocial.fields.done') }}</option>
                                <option value="1"
                                    @isset($done) @if ($done == '1') selected @endif @endisset>
                                    تم التسليم</option>
                                <option value="0"
                                    @isset($done) @if ($done == '0') selected @endif @endisset>
                                    لم يتم التسليم</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('global.extra.returned') }}</label>
                            <select class="form-control @isset($returned) isset @endisset"
                                name="returned" id="returned">
                                <option value="">{{ __('global.extra.returned') }}</option>
                                <option value="1"
                                    @isset($returned) @if ($returned == '1') selected @endif @endisset>
                                    مرتجع</option>
                                <option value="0"
                                    @isset($returned) @if ($returned == '0') selected @endif @endisset>
                                    غير مرتجع</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.supplied') }}</label>
                            <select class="form-control @isset($supplied) isset @endisset"
                                name="supplied" id="supplied">
                                <option value="">{{ __('cruds.receiptSocial.fields.supplied') }}</option>
                                <option value="1"
                                    @isset($supplied) @if ($supplied == '1') selected @endif @endisset>
                                    تم التوريد</option>
                                <option value="0"
                                    @isset($supplied) @if ($supplied == '0') selected @endif @endisset>
                                    لم يتم التوريد</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.delivery_status') }}</label>
                            <select class="form-control @isset($delivery_status) isset @endisset"
                                name="delivery_status" id="delivery_status">
                                <option value="">{{ __('cruds.receiptSocial.fields.delivery_status') }}</option>
                                @foreach (__('global.delivery_status.status') as $key => $status)
                                    <option value="{{ $key }}"
                                        @isset($delivery_status) @if ($delivery_status == $key) selected @endif @endisset>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.payment_status') }}</label>
                            <select class="form-control @isset($payment_status) isset @endisset"
                                name="payment_status" id="payment_status">
                                <option value="">{{ __('cruds.receiptSocial.fields.payment_status') }}</option>
                                @foreach (__('global.payment_status.status') as $key => $status)
                                    <option value="{{ $key }}"
                                        @isset($payment_status) @if ($payment_status == $key) selected @endif @endisset>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.playlist_status') }}</label>
                            <select class="form-control @isset($playlist_status) isset @endisset"
                                name="playlist_status" id="playlist_status">
                                <option value="">{{ __('cruds.receiptSocial.fields.playlist_status') }}</option>
                                @foreach (__('global.playlist_status.status') as $key => $status)
                                    <option value="{{ $key }}"
                                        @isset($playlist_status) @if ($playlist_status == $key) selected @endif @endisset>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Financial Tab -->
                <div class="tab-pane" id="financial-tab">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label>سعر الأوردر</label>
                            <input type="number"
                                class="form-control @isset($total_cost) isset @endisset"
                                id="total_cost" name="total_cost"
                                @isset($total_cost) value="{{ $total_cost }}" @endisset
                                placeholder="أكثر من">
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.deposit_type') }}</label>
                            <select class="form-control @isset($deposit_type) isset @endisset"
                                name="deposit_type" id="deposit_type">
                                <option value="">{{ __('cruds.receiptSocial.fields.deposit_type') }}</option>
                                @foreach (\App\Models\ReceiptSocial::DEPOSIT_TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}"
                                        @isset($deposit_type) @if ($deposit_type == $key) selected @endif @endisset>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.financial_account_id') }}</label>
                            <select class="form-control @isset($financial_account_id) isset @endisset"
                                name="financial_account_id" id="financial_account_id">
                                <option value="">{{ __('cruds.receiptSocial.fields.financial_account_id') }}
                                </option>
                                @foreach ($financial_accounts as $raw)
                                    <option value="{{ $raw->id }}"
                                        @isset($financial_account_id) @if ($financial_account_id == $raw->id) selected @endif @endisset>
                                        {{ $raw->account }} - {{ $raw->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Dates Tab -->
                <div class="tab-pane" id="dates-tab">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label>{{ __('global.extra.date_type') }}</label>
                            <select class="form-control @isset($date_type) isset @endisset"
                                name="date_type" id="date_type">
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
                        <div class="filter-item">
                            <label>{{ __('global.extra.from_date') }}</label>
                            <input type="text"
                                class="form-control date @isset($from_date) isset @endisset"
                                @isset($from_date) value="{{ request('from_date') }}" @endisset
                                name="from_date" id="from_date" placeholder="{{ __('global.extra.from_date') }}">
                        </div>
                        <div class="filter-item">
                            <label>{{ __('global.extra.to_date') }}</label>
                            <input type="text"
                                class="form-control date @isset($to_date) isset @endisset"
                                @isset($to_date) value="{{ request('to_date') }}" @endisset
                                name="to_date" id="to_date" placeholder="{{ __('global.extra.to_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Advanced Tab -->
                <div class="tab-pane" id="advanced-tab">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.delivery_man_id') }}</label>
                            <select class="form-control @isset($delivery_man_id) isset @endisset"
                                name="delivery_man_id" id="delivery_man_id">
                                <option value="">{{ __('cruds.receiptSocial.fields.delivery_man_id') }}</option>
                                @foreach ($delivery_mans as $raw)
                                    <option value="{{ $raw->id }}"
                                        @isset($delivery_man_id) @if ($delivery_man_id == $raw->id) selected @endif @endisset>
                                        {{ $raw->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.shipping_country_id') }}</label>
                            <select class="form-control select2" name="country_id" id="country_id">
                                <option value="">{{ __('cruds.receiptSocial.fields.shipping_country_id') }}
                                </option>
                                @if (isset($countries['districts']))
                                    <optgroup label="{{ __('Districts') }}">
                                        @foreach ($countries['districts'] as $district)
                                            <option value={{ $district->id }}
                                                @if ($district->id == $country_id) selected @endif>
                                                {{ $district->name }} - {{ dashboard_currency($district->cost) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if (isset($countries['countries']))
                                    <optgroup label="{{ __('Countries') }}">
                                        @foreach ($countries['countries'] as $country)
                                            <option value={{ $country->id }}
                                                @if ($country->id == $country_id) selected @endif>
                                                {{ $country->name }} - {{ dashboard_currency($country->cost) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if (isset($countries['metro']))
                                    <optgroup label="{{ __('Metro') }}">
                                        @foreach ($countries['metro'] as $raw)
                                            <option value={{ $raw->id }}
                                                @if ($raw->id == $country_id) selected @endif>
                                                {{ $raw->name }} - {{ dashboard_currency($raw->cost) }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('cruds.receiptSocial.fields.socials') }}</label>
                            <select class="form-control @isset($social_id) isset @endisset"
                                name="social_id" id="social_id">
                                <option value="">{{ __('cruds.receiptSocial.fields.socials') }}</option>
                                @foreach ($socials as $social)
                                    <option value="{{ $social->id }}"
                                        @isset($social_id) @if ($social_id == $social->id) selected @endif @endisset>
                                        {{ $social->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>{{ __('global.extra.description') }}</label>
                            <input type="text"
                                class="form-control @isset($description) isset @endisset"
                                id="description" name="description"
                                @isset($description) value="{{ $description }}" @endisset
                                placeholder="{{ __('global.extra.description') }}">
                        </div>
                        <div class="filter-item">
                            <label>{{ __('global.extra.from_order') }}</label>
                            <input type="text"
                                class="form-control @isset($from) isset @endisset"
                                name="from" id="from"
                                @isset($from) value="{{ $from }}" @endisset
                                placeholder="رقم أوردر">
                        </div>
                        <div class="filter-item">
                            <label>{{ __('global.extra.to_order') }}</label>
                            <input type="text"
                                class="form-control @isset($to) isset @endisset"
                                name="to" id="to"
                                @isset($to) value="{{ $to }}" @endisset
                                placeholder="رقم أوردر">
                        </div>
                        <div class="filter-item">
                            <label>{{ __('global.extra.exclude_orders') }}</label>
                            <input type="text"
                                class="form-control @isset($exclude) isset @endisset"
                                name="exclude" id="exclude"
                                @isset($exclude) value="{{ $exclude }}" @endisset
                                placeholder="أضافة رقم أوردر" data-role="tagsinput">
                        </div>
                        <div class="filter-item">
                            <label>{{ __('global.extra.include_orders') }}</label>
                            <input type="text"
                                class="form-control @isset($include) isset @endisset"
                                name="include" id="include"
                                @isset($include) value="{{ $include }}" @endisset
                                placeholder="أضافة رقم أوردر" data-role="tagsinput">
                        </div>
                        <div class="filter-item">
                            <label>المنتجات</label>
                            <select class="form-control select2 @isset($selectedProducts) isset @endisset" 
                                name="selectedProducts[]" id="selectedProducts" multiple> 
                                @foreach ($receiptSocialProducts as $raw)
                                    <option value="{{ $raw->id }}"
                                        @isset($selectedProducts) @if (in_array($raw->id, $selectedProducts)) selected @endif @endisset>
                                        {{ $raw->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="filter-actions">
                <div class="action-buttons">
                    <button type="submit" name="search" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>{{ __('global.search') }}
                    </button>
                    @if (Gate::allows('download_receipts'))
                        <button type="submit" name="download" class="btn btn-info">
                            <i class="fas fa-download me-2"></i>{{ __('global.download') }}
                        </button>
                    @endif
                    @can('receipt_social_print')
                        <button type="submit" name="print" class="btn btn-danger">
                            <i class="fas fa-print me-2"></i>{{ __('global.print') }}
                        </button>
                    @endcan
                    <button type="submit" name="download_delivery_fedex" class="btn btn-dark">
                        <i class="fas fa-shipping-fast me-2"></i>Fedex
                    </button>
                    <button type="submit" name="download_delivery_smsa" class="btn btn-dark">
                        <i class="fas fa-truck me-2"></i>Smsa
                    </button>
                    <a class="btn btn-warning" href="{{ route('admin.receipt-socials.index') }}">
                        <i class="fas fa-undo me-2"></i>{{ __('global.reset') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</form>
<style>
    .modern-search-container {
        position: relative;
        margin-bottom: 2rem;
    }

    .floating-search-bar {
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }

    .search-input-group {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .search-icon {
        color: white;
        font-size: 1.2rem;
    }

    .modern-search-input {
        flex: 1;
        border: none;
        border-radius: 25px;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    .modern-search-input:focus {
        outline: none;
        background: white;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
    }

    .search-actions {
        display: flex;
        gap: 0.5rem;
    }

    .advanced-filters-panel {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: none;
    }

    .advanced-filters-panel.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    .filter-tabs {
        display: flex;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .filter-tab {
        flex: 1;
        border: none;
        background: transparent;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
    }

    .filter-tab.active {
        background: white;
        border-bottom-color: #667eea;
        color: #667eea;
    }

    .filter-tab:hover {
        background: rgba(102, 126, 234, 0.1);
    }

    .tab-content {
        padding: 2rem;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .filter-item label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #495057;
    }

    .filter-actions {
        background: #f8f9fa;
        padding: 1.5rem;
        border-top: 1px solid #dee2e6;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .search-input-group {
            flex-direction: column;
            gap: 1rem;
        }

        .search-actions {
            width: 100%;
            justify-content: center;
        }

        .filter-tabs {
            flex-wrap: wrap;
        }

        .filter-tab {
            flex: 1 1 50%;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<script>
    function toggleAdvancedFilters() {
        const panel = document.getElementById('advancedFiltersPanel');
        panel.classList.toggle('show');
    }

    function submitSearch() {
        document.getElementById('sort_receipt_social').submit();
    }

    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.filter-tab');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');

                // Remove active class from all tabs and panes
                tabs.forEach(t => t.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));

                // Add active class to clicked tab and corresponding pane
                this.classList.add('active');
                document.getElementById(targetTab + '-tab').classList.add('active');
            });
        });

        // Initialize Bootstrap components properly
        if (typeof bootstrap !== 'undefined') {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        }
    });

    // Global search functionality
    document.getElementById('globalSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.receipt-row');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
