<!-- Modern Table Design -->
<div class="modern-table-container">
    <!-- Table Header -->
    <div class="table-header">
        <div class="header-content">
            <h4 class="mb-0">
                <i class="fas fa-table me-2"></i>
                {{ __('global.list') }} {{ __('cruds.receiptSocial.title') }}
                @isset($deleted)
                    {{ __('global.deleted') }}
                @endisset
            </h4>
            <div class="header-actions">
                <div class="results-info">
                    @isset($order_num)
                        <span class="badge bg-primary filter-badge">
                            <span>رقم الفاتورة:</span> {{ $order_num }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('order_num')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($client_name)
                        <span class="badge bg-info filter-badge">
                            <span>اسم العميل:</span> {{ $client_name }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('client_name')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($phone)
                        <span class="badge bg-success filter-badge">
                            <span>رقم الهاتف:</span> {{ $phone }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('phone')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($general_search)
                        <span class="badge bg-warning filter-badge">
                            <span>بحث عام:</span> {{ $general_search }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('general_search')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($client_type)
                        <span class="badge bg-secondary text-dark filter-badge">
                            <span>{{ __('cruds.receiptSocial.fields.client_type') }}:</span> {{ \App\Models\ReceiptSocial::CLIENT_TYPE_SELECT[$client_type] ?? $client_type }}
                            <button type="button" class="btn-close btn-close-dark ms-1" onclick="removeFilter('client_type')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($delivery_status)
                        <span class="badge bg-dark filter-badge">
                            <span>{{ __('cruds.receiptSocial.fields.delivery_status') }}:</span> {{ __('global.delivery_status.status.' . $delivery_status) }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('delivery_status')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($payment_status)
                        <span class="badge bg-primary filter-badge">
                            <span>{{ __('cruds.receiptSocial.fields.payment_status') }}:</span> {{ __('global.payment_status.status.' . $payment_status) }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('payment_status')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($staff_id)
                        @php
                            $staff = \App\Models\User::find($staff_id);
                            $staffName = $staff ? $staff->name : 'غير محدد';
                        @endphp
                        <span class="badge bg-info filter-badge">
                            <span>الموظف:</span> {{ $staffName }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('staff_id')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($delivery_man_id)
                        @php
                            $deliveryMan = \App\Models\User::find($delivery_man_id);
                            $deliveryManName = $deliveryMan ? $deliveryMan->name : 'غير محدد';
                        @endphp
                        <span class="badge bg-success filter-badge">
                            <span>مندوب التوصيل:</span> {{ $deliveryManName }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('delivery_man_id')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($social_id)
                        @php
                            $social = \App\Models\Social::find($social_id);
                            $socialName = $social ? $social->name : 'غير محدد';
                        @endphp
                        <span class="badge bg-warning filter-badge">
                            <span>الوسائط الاجتماعية:</span> {{ $socialName }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('social_id')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($country_id)
                        @php
                            $country = \App\Models\Country::find($country_id);
                            $countryName = $country ? $country->name : 'غير محدد';
                        @endphp
                        <span class="badge bg-secondary text-dark filter-badge">
                            <span>الدولة:</span> {{ $countryName }}
                            <button type="button" class="btn-close btn-close-dark ms-1" onclick="removeFilter('country_id')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($website_setting_id)
                        @php
                            $website = \App\Models\WebsiteSetting::find($website_setting_id);
                            $websiteName = $website ? $website->site_name : 'غير محدد';
                        @endphp
                        <span class="badge bg-primary filter-badge">
                            <span>الموقع:</span> {{ $websiteName }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('website_setting_id')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($financial_account_id)
                        @php
                            $financialAccount = \App\Models\FinancialAccount::find($financial_account_id);
                            $financialAccountName = $financialAccount ? $financialAccount->description : 'غير محدد';
                        @endphp
                        <span class="badge bg-info filter-badge">
                            <span>الحساب المالي:</span> {{ $financialAccountName }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('financial_account_id')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($description)
                        <span class="badge bg-dark filter-badge">
                            <span>وصف المنتج:</span> {{ $description }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('description')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($from)
                        <span class="badge bg-success filter-badge">
                            <span>من:</span> {{ $from }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('from')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($to)
                        <span class="badge bg-success filter-badge">
                            <span>إلى:</span> {{ $to }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('to')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($from_date)
                        <span class="badge bg-warning filter-badge">
                            <span>من تاريخ:</span> {{ $from_date }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('from_date')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($to_date)
                        <span class="badge bg-warning filter-badge">
                            <span>إلى تاريخ:</span> {{ $to_date }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('to_date')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($date_type)
                        <span class="badge bg-secondary text-dark filter-badge">
                            <span>نوع التاريخ:</span> {{ __('cruds.receiptSocial.fields.' . $date_type) }}
                            <button type="button" class="btn-close btn-close-dark ms-1" onclick="removeFilter('date_type')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($exclude)
                        <span class="badge bg-danger filter-badge">
                            <span>استثناء:</span> {{ $exclude }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('exclude')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($include)
                        <span class="badge bg-success filter-badge">
                            <span>تضمين:</span> {{ $include }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('include')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($sent_to_delivery)
                        <span class="badge bg-info filter-badge">
                            <span>{{ $sent_to_delivery ? 'تم الإرسال للتوصيل' : 'لم يتم الإرسال للتوصيل' }}</span>
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('sent_to_delivery')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($confirm)
                        <span class="badge bg-{{ $confirm ? 'success' : 'warning' }} filter-badge">
                            <span>{{ $confirm ? 'تم التأكيد' : 'لم يتم التأكيد' }}</span>
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('confirm')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($done)
                        <span class="badge bg-{{ $done ? 'success' : 'warning' }} filter-badge">
                            <span>{{ $done ? 'تم التسليم' : 'لم يتم التسليم' }}</span>
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('done')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($supplied)
                        <span class="badge bg-{{ $supplied ? 'success' : 'warning' }} filter-badge">
                            <span>{{ $supplied ? 'تم التوريد' : 'لم يتم التوريد' }}</span>
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('supplied')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($returned)
                        <span class="badge bg-{{ $returned ? 'danger' : 'success' }} filter-badge">
                            <span>{{ $returned ? 'مرتجع' : 'غير مرتجع' }}</span>
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('returned')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($quickly)
                        <span class="badge bg-{{ $quickly ? 'warning' : 'secondary text-dark' }} filter-badge">
                            <span>{{ $quickly ? 'سريع' : 'عادي' }}</span>
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('quickly')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($playlist_status)
                        <span class="badge bg-{{ __('global.playlist_status.colors.' . $playlist_status) }} filter-badge">
                            <span>{{ __('global.playlist_status.status.' . $playlist_status) }}</span>
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('playlist_status')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($deposit_type)
                        <span class="badge bg-info filter-badge">
                            <span>نوع العربون:</span> {{ $deposit_type }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('deposit_type')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($total_cost)
                        <span class="badge bg-success filter-badge">
                            <span>التكلفة الإجمالية:</span> {{ dashboard_currency($total_cost) }}
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('total_cost')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($product_type)
                        <span class="badge bg-info filter-badge">
                            <span>{{ $product_type == 1 ? 'منتجات موسمية' : 'منتجات عادية' }}</span>
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('product_type')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($isShopify)
                        <span class="badge bg-{{ $isShopify ? 'primary' : 'secondary text-dark' }} filter-badge">
                            <span>{{ $isShopify ? 'طلب شوبيفاي' : 'طلب غير شوبيفاي' }}</span>
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('isShopify')" aria-label="Remove filter"></button>
                        </span>
                    @endisset 
                    @isset($selectedProducts)
                        <span class="badge bg-success filter-badge">
                            <span>المنتجات:</span> 
                            @foreach ($selectedProducts as $product)
                                <span class="badge bg-info">
                                    {{ $receiptSocialProducts->where('id', $product)->first()->name }}
                                </span>
                            @endforeach
                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('selectedProducts[]')" aria-label="Remove filter"></button>
                        </span>
                    @endisset
                </div>
                <div class="view-toggle">
                    <button class="btn btn-secondary text-dark btn-sm me-2" onclick="toggleAllCards()" id="collapseAllBtn">
                        <i class="fas fa-compress-alt me-1"></i>
                        <span id="collapseAllText">Collapse All</span>
                    </button>
                    <button class="btn btn-outline-primary btn-sm" onclick="toggleTableView()">
                        <i class="fas fa-th-large" id="viewToggleIcon"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Content -->
    <div class="table-content" id="tableContent">
        @forelse ($receipts as $key => $receipt)
            <div class="receipt-card @if($receipt->quickly) quickly @elseif($receipt->returned) returned @elseif($receipt->done) done @endif" 
                data-entry-id="{{ $receipt->id }}"
                onclick="toggleCard(this)">
                
                <!-- Card Header -->
                <div class="card-header-section">
                    <div class="order-info">
                        <div class="order-number">
                            <span class="order-badge order_num badge rounded-pill
                                @if($receipt->website_setting_id == 2) order_num_ertgal 
                                @elseif($receipt->website_setting_id == 3) order_num_figures 
                                @elseif($receipt->website_setting_id == 4) order_num_shirti 
                                @elseif($receipt->website_setting_id == 5) order_num_martobia
                                @elseif($receipt->website_setting_id == 6) order_num_a1_digital
                                @elseif($receipt->website_setting_id == 7) order_num_ein
                                @else order_num_ebtekar @endif text-white" 
                                onclick="show_logs('App\\Models\\ReceiptSocial','{{ $receipt->id }}','receiptSocial')">
                                @if($receipt->printing_times == 0) 
                                    <span class="badge rounded-pill text-bg-primary text-white me-1">new</span>
                                @else
                                    <span class="badge rounded-pill text-bg-primary text-white me-1">{{ $receipt->printing_times }}</span>
                                @endif
                                {{ $receipt->order_num ?? '' }}
                            </span>
                        </div>
                        <div class="order-meta">
                            <span class="order-number-meta">
                                <span class="order-number-meta-item">
                                    <i class="fas fa-qrcode" onclick="show_qr_code('{{$receipt->order_num}}','s-{{$receipt->id}}')" style="cursor: pointer"></i>
                                </span> 
                            </span>
                            <span class="client-type">
                                {{ \App\Models\ReceiptSocial::CLIENT_TYPE_SELECT[$receipt->client_type] }}
                            </span>
                            @if($receipt->shopify_id)
                                <span class="shopify-badge">
                                    <i class="fab fa-shopify"></i>
                                    Shopify #{{ $receipt->shopify_order_num }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-actions">
                        <div class="dropdown">
                            <button class="btn btn-secondary text-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @can('receipt_social_view_products')
                                    <a class="dropdown-item" onclick="view_products('{{ $receipt->id }}')">
                                        <i class="fas fa-eye me-2"></i>{{ __('global.extra.view_products') }}
                                    </a>
                                @endcan
                                @if(!$receipt->hold || auth()->user()->is_admin)
                                    @if(!isset($deleted))
                                        @can('receipt_social_add_product')
                                            <a class="dropdown-item" onclick="add_product('{{ $receipt->id }}')">
                                                <i class="fas fa-plus-circle me-2"></i>{{ __('global.extra.add_product') }}
                                            </a>
                                        @endcan 
                                        @can('receipt_social_edit')
                                            <a class="dropdown-item" href="{{ route('admin.receipt-socials.edit', $receipt->id) }}">
                                                <i class="far fa-edit me-2"></i>{{ __('global.edit') }}
                                            </a>
                                        @endcan
                                        @can('receipt_social_print')
                                            <a class="dropdown-item" target="print-frame" href="{{ route('admin.receipt-socials.print', $receipt->id) }}">
                                                <i class="fas fa-print me-2"></i>{{ __('global.print') }}
                                            </a>
                                        @endcan
                                        @can('receipt_social_duplicate')
                                            <a class="dropdown-item" href="{{ route('admin.receipt-socials.duplicate', $receipt->id) }}">
                                                <i class="far fa-clone me-2"></i>{{ __('global.duplicate') }}
                                            </a>
                                        @endcan
                                        @can('receipt_social_receive_money')
                                            <a class="dropdown-item" target="print-frame" href="{{ route('admin.receipt-socials.receive_money', $receipt->id) }}">
                                                <i class="fas fa-money-bill-wave me-2"></i>{{ __('global.receive_money') }}
                                            </a>
                                        @endcan
                                    @else  
                                        @can('receipt_social_restore')
                                            <a class="dropdown-item" href="{{ route('admin.receipt-socials.restore', $receipt->id) }}">
                                                <i class="fas fa-undo me-2"></i>{{ __('global.restore') }}
                                            </a>  
                                        @endcan
                                    @endif
                                    @can('receipt_social_delete')
                                        <?php $route = route('admin.receipt-socials.destroy', $receipt->id); ?>
                                        <a class="dropdown-item" href="#" onclick="deleteConfirmation('{{$route}}')">
                                            <i class="fas fa-trash-alt me-2"></i>{{ __('global.delete') }} @isset($deleted) {{ __('global.permanently') }} @endisset
                                        </a>
                                    @endcan
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body-section">
                    <div class="client-section">
                        <div class="client-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="client-details">
                            <h6 class="client-name">{{ $receipt->client_name ?? '' }}</h6>
                            <div class="contact-info">
                                <span class="phone">
                                    <i class="fas fa-phone text-success me-1"></i>
                                    {{ $receipt->phone_number ?? '' }}
                                </span>
                                @if($receipt->phone_number_2)
                                    <span class="phone">
                                        <i class="fas fa-phone text-success me-1"></i>
                                        {{ $receipt->phone_number_2 ?? '' }}
                                    </span>
                                @endif
                            </div>
                            @if ($receipt->socials)
                                <div class="social-tags">
                                    @foreach ($receipt->socials as $social)
                                        <span class="social-tag">{{ $social ? $social->name : '' }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="financial-section">
                        <div class="financial-summary">
                            @if($receipt->deposit > 0)
                            <div class="amount-item">
                                <span class="label">العربون:</span>
                                <span class="value">{{ dashboard_currency($receipt->deposit) }}</span>
                            </div>
                            @endif
                            @if($receipt->extra_commission > 0)
                            <div class="amount-item">
                                <span class="label">العمولة:</span>
                                <span class="value">{{ dashboard_currency($receipt->extra_commission) }}</span>
                            </div>
                            @endif 
                            @if($receipt->discounted_amount > 0)
                            <div class="amount-item">
                                <span class="label">الخصم:</span>
                                <span class="value">{{ dashboard_currency($receipt->discounted_amount) }}</span>
                            </div>
                            @endif 
                            <div class="amount-item">
                                <span class="label">الشحن:</span>
                                <span class="value">{{ dashboard_currency($receipt->shipping_country_cost) }}</span>
                            </div>  
                            <div class="amount-item">
                                <span class="label">مجموع المنتجات:</span>
                                <span class="value">{{ dashboard_currency($receipt->total_cost) }}</span>
                            </div>
                            <div class="amount-item total">
                                <span class="label">المجموع:</span>
                                <span class="value">{{ dashboard_currency($receipt->calc_total_for_client()) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="shipping-section">
                        <div class="shipping-info">
                            <div class="shipping-location">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <span class="location-name">{{ $receipt->shipping_country ? $receipt->shipping_country->name : '' }}</span>
                            </div>
                            <div class="shipping-address">
                                <i class="fas fa-home text-muted me-2"></i>
                                <span class="address-text">{{ $receipt->shipping_address ?? '' }}</span>
                            </div>
                            @if($receipt->delivery_man)
                                <div class="delivery-man">
                                    <i class="fas fa-truck text-info me-2"></i>
                                    <span class="delivery-name">{{ $receipt->delivery_man->name ?? '' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="status-section">
                        <div class="status-grid">
                            <div class="status-item">
                                <span class="status-label">التأكيد</span>
                                <label class="c-switch c-switch-pill c-switch-success">
                                    <input onchange="update_statuses(this,'confirm')" value="{{ $receipt->id }}"
                                        type="checkbox" class="c-switch-input"
                                        {{ $receipt->confirm ? 'checked' : null }}>
                                    <span class="c-switch-slider"></span>
                                </label>
                            </div>
                            <div class="status-item">
                                <span class="status-label">التسليم</span>
                                @if(Gate::allows('done'))
                                    <label class="c-switch c-switch-pill c-switch-success">
                                        <input onchange="update_statuses(this,'done')" value="{{ $receipt->id }}"
                                            type="checkbox" class="c-switch-input"
                                            {{ $receipt->done ? 'checked' : null }}>
                                        <span class="c-switch-slider"></span>
                                    </label>
                                @else
                                    <div class="status-indicator @if($receipt->done) delivered @else pending @endif">
                                        <i class="fas @if($receipt->done) fa-check-circle @else fa-clock @endif"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="status-item">
                                <span class="status-label">التوريد</span>
                                <div id="supplied-{{$receipt->id}}">
                                    @if($receipt->supplied)
                                        <i class="far fa-check-circle" style="padding: 5px; font-size: 20px; color: green;"></i>
                                    @else
                                        @if(Gate::allows('supplied'))
                                            <label class="c-switch c-switch-pill c-switch-success">
                                                <input onchange="update_statuses(this,'supplied')" value="{{ $receipt->id }}"
                                                    type="checkbox" class="c-switch-input"
                                                    {{ $receipt->supplied ? 'checked' : null }}>
                                                <span class="c-switch-slider"></span>
                                            </label>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="status-item">
                                <span class="status-label">المرتجع</span>
                                <label class="c-switch c-switch-pill c-switch-success">
                                    <input onchange="update_statuses(this,'returned')" value="{{ $receipt->id }}"
                                        type="checkbox" class="c-switch-input"
                                        {{ $receipt->returned ? 'checked' : null }}>
                                    <span class="c-switch-slider"></span>
                                </label>
                            </div>
                            <div class="status-item">
                                <span class="status-label">سريع</span>
                                <label class="c-switch c-switch-pill c-switch-success">
                                    <input onchange="update_statuses(this,'quickly')" value="{{ $receipt->id }}"
                                        type="checkbox" class="c-switch-input"
                                        {{ $receipt->quickly ? 'checked' : null }}>
                                    <span class="c-switch-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="dates-section">
                        <div class="date-item">
                            <i class="fas fa-calendar-plus text-primary" title="تاريخ الانشاء"></i>
                            <span>{{ $receipt->created_at }}</span>
                        </div>
                        @if($receipt->date_of_receiving_order)
                            <div class="date-item">
                                <i class="fas fa-calendar-check text-success" title="تاريخ الاستلام"></i>
                                <span>{{ $receipt->date_of_receiving_order }}</span>
                            </div>
                        @endif
                        @if($receipt->send_to_delivery_date)
                            <div class="date-item">
                                <i class="fas fa-truck text-info" title="تاريخ التسليم"></i>
                                <span>{{ $receipt->send_to_delivery_date }}</span>
                            </div>
                        @endif
                        @if($receipt->send_to_playlist_date)
                            <div class="date-item">
                                <i class="fas fa-play-circle text-dark" title="تاريخ الارسال لمراحل التشغيل"></i>
                                <span>{{ $receipt->send_to_playlist_date }}</span>
                            </div>
                        @endif
                    </div>

                    @if($receipt->note)
                        <div class="note-section">
                            <i class="fas fa-sticky-note text-warning me-2"></i>
                            <span class="note-text">{{ Str::limit($receipt->note, 100) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Card Footer -->
                <div class="card-footer-section">
                    <div class="footer-actions">
                        @can('hold')
                            <form action="{{ route('admin.receipt-socials.update_statuses') }}" method="POST" style="display: inline" id="hold-form-{{ $receipt->id }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $receipt->id }}">
                                <input type="hidden" name="type" value="hold">
                                <input type="hidden" name="hold_reason" id="hold-reason-{{ $receipt->id }}" value="{{ $receipt->hold_reason }}">
                                @if($receipt->hold == 0)
                                    <input type="hidden" name="status" value="1">
                                    <button type="button" class="btn btn-dark btn-sm" onclick="showHoldModal('{{ $receipt->id }}','{{ $receipt->hold_reason }}')">
                                        <i class="fas fa-pause me-1"></i>Hold
                                    </button>
                                @else 
                                    <input type="hidden" name="status" value="0">
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-play me-1"></i>UnHold
                                    </button> 
                                    @if($receipt->hold_reason)
                                        <span class="badge bg-info text-white ms-1" style="cursor: pointer" 
                                            data-toggle="tooltip" 
                                            data-placement="top" 
                                            title="{{ $receipt->hold_reason }}">
                                            Hold Reason
                                            <i class="fas fa-info-circle"></i>
                                        </span>
                                    @endif
                                @endif
                            </form>
                        @endcan

                        @if($receipt->playlist_status == 'pending')
                            @if($receipt->receipts_receipt_social_products_count  > 0) 
                                <button class="btn btn-danger btn-sm" onclick="playlist_users('{{$receipt->id}}','social')">
                                    <i class="fas fa-play me-1"></i>أرسال لمراحل التشغيل
                                </button>  
                            @endif
                        @else  
                            <span onclick="playlist_users('{{$receipt->id}}','social')" 
                                class="playlist_status badge text-bg-{{ __('global.playlist_status.colors.' . $receipt->playlist_status) }}">
                                <i class="fas fa-play-circle me-1"></i>
                                {{ $receipt->playlist_status ? __('global.playlist_status.status.' . $receipt->playlist_status) : '' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h5>لا توجد بيانات متاحة</h5>
                <p>لم يتم العثور على أي فواتير تطابق معايير البحث</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pagination-section">
        <div class="pagination-info">
            <small class="text-muted">
                عرض {{ $receipts->firstItem() ?? 0 }} إلى {{ $receipts->lastItem() ?? 0 }} من {{ $receipts->total() }} نتيجة
            </small>
        </div>
        <div class="pagination-links">
            {{ $receipts->appends(request()->input())->links() }}
        </div>
    </div>
</div>

<style>
.modern-table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

.table-header { 
    color: white;
    padding: 1.5rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.results-info {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    max-width: 100%;
}

.results-info .badge {
    margin-bottom: 0.25rem;
    font-size: 0.75rem;
    white-space: nowrap;
}

.table-content {
    padding: 1.5rem;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
}

.table-content.list-view {
    display: block;
    padding: 0;
}

.table-content.list-view .receipt-card {
    margin-bottom: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.table-content.list-view .receipt-card:hover {
    transform: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.table-content.list-view .receipt-card {
    display: flex;
    flex-direction: column;
}

.table-content.list-view .card-header-section {
    border-bottom: 1px solid #f1f3f4;
}

.table-content.list-view .card-body-section {
    flex: 1;
}

.table-content.list-view .card-footer-section {
    border-top: 1px solid #f1f3f4;
    margin-top: auto;
}

.receipt-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    overflow: hidden;
}

.receipt-card.collapsed .card-body-section {
    display: none;
}

.receipt-card.collapsed .card-footer-section {
    display: none;
}

.receipt-card.collapsed {
    max-height: 80px;
    overflow: hidden;
}

.receipt-card.quickly {
    border-left-color: #ffc107;
}

.receipt-card.returned {
    border-left-color: #dc3545;
}

.receipt-card.done {
    border-left-color: #28a745;
}

.receipt-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    cursor: pointer;
}

.receipt-card.collapsed:hover {
    transform: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.card-header-section {
    padding: 1rem;
    border-bottom: 1px solid #f1f3f4;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.order-info {
    flex: 1;
}

.order-number {
    margin-bottom: 0.5rem;
}

.order-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.client-type {
    background: #e9ecef;
    color: #495057;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
}

.shopify-badge {
    background: #6f42c1;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
}

.card-body-section {
    padding: 1rem;
}

.client-section {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.client-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.client-details {
    flex: 1;
}

.client-name {
    margin: 0 0 0.5rem 0;
    font-weight: 600;
    color: #2c3e50;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.phone {
    font-size: 0.9rem;
    color: #6c757d;
}

.social-tags {
    display: flex;
    gap: 0.25rem;
    flex-wrap: wrap;
}

.social-tag {
    background: #17a2b8;
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 8px;
    font-size: 0.75rem;
}

.financial-section {
    margin-bottom: 1rem;
}

.financial-summary {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.shipping-section {
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 3px solid #17a2b8;
}

.shipping-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.shipping-location {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #495057;
}

.location-name {
    font-size: 0.9rem;
}

.shipping-address {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    color: #6c757d;
    font-size: 0.85rem;
    line-height: 1.4;
}

.address-text {
    word-wrap: break-word;
    flex: 1;
}

.delivery-man {
    display: flex;
    align-items: center;
    color: #17a2b8;
    font-size: 0.85rem;
    font-weight: 500;
}

.delivery-name {
    font-weight: 600;
}

.amount-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.amount-item.total {
    border-top: 1px solid #dee2e6;
    padding-top: 0.5rem;
    font-weight: 600;
    color: #28a745;
}

.status-section {
    margin-bottom: 1rem;
}

.status-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}

.status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.status-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.status-indicator {
    font-size: 1rem;
}

.status-indicator.confirmed,
.status-indicator.delivered,
.status-indicator.supplied {
    color: #28a745;
}

.status-indicator.pending {
    color: #ffc107;
}

.status-indicator.returned {
    color: #dc3545;
}

.status-indicator.normal {
    color: #6c757d;
}

.dates-section {
    margin-bottom: 1rem;
}

.date-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.note-section {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 0.75rem;
    font-size: 0.85rem;
    color: #856404;
}

.card-footer-section {
    padding: 1rem;
    border-top: 1px solid #f1f3f4;
    background: #f8f9fa;
}

.footer-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.pagination-section {
    padding: 1.5rem;
    border-top: 1px solid #f1f3f4;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Toggle Switch Styles */
.c-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
    margin: 0;
}

.c-switch-input {
    opacity: 0;
    width: 0;
    height: 0;
    margin: 0;
}

.c-switch-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.c-switch-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

.c-switch-input:checked + .c-switch-slider {
    background-color: #28a745;
}

.c-switch-input:checked + .c-switch-slider:before {
    transform: translateX(26px);
}

.c-switch-pill .c-switch-slider {
    border-radius: 24px;
}

.c-switch-pill .c-switch-slider:before {
    border-radius: 50%;
}

.c-switch-success .c-switch-slider {
    background-color: #6c757d;
}

.c-switch-success .c-switch-input:checked + .c-switch-slider {
    background-color: #28a745;
}

/* Filter Badge Styles */
.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
    border-radius: 20px;
    transition: all 0.2s ease;
    cursor: default;
}

.filter-badge .btn-close {
    width: 12px;
    height: 12px;
    padding: 0;
    margin: 0;
    background-size: 12px;
    opacity: 0.7;
    transition: opacity 0.2s ease;
}

.filter-badge .btn-close:hover {
    opacity: 1;
    transform: scale(1.1);
}

.filter-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}

.btn-close-dark {
    filter: none;
}

@media (max-width: 768px) {
    .table-content {
        grid-template-columns: 1fr;
    }
    
    .header-content {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .header-actions {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .results-info {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .results-info .badge {
        align-self: flex-start;
    }
    
    .pagination-section {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>

<script>
function toggleTableView() {
    const tableContent = document.getElementById('tableContent');
    const viewToggleIcon = document.getElementById('viewToggleIcon');
    
    if (tableContent.classList.contains('list-view')) {
        // Switch to grid view
        tableContent.classList.remove('list-view');
        tableContent.classList.add('grid-view');
        viewToggleIcon.classList.remove('fa-list');
        viewToggleIcon.classList.add('fa-th-large');
    } else {
        // Switch to list view
        tableContent.classList.remove('grid-view');
        tableContent.classList.add('list-view');
        viewToggleIcon.classList.remove('fa-th-large');
        viewToggleIcon.classList.add('fa-list');
    }
}

// Initialize as grid view
document.addEventListener('DOMContentLoaded', function() {
    const tableContent = document.getElementById('tableContent');
    tableContent.classList.add('grid-view');
});

function toggleAllCards() {
    const cards = document.querySelectorAll('.receipt-card');
    const collapseAllBtn = document.getElementById('collapseAllBtn');
    const collapseAllText = document.getElementById('collapseAllText');
    const collapseAllIcon = collapseAllBtn.querySelector('i');
    
    // Check if all cards are currently collapsed
    const allCollapsed = Array.from(cards).every(card => card.classList.contains('collapsed'));
    
    if (allCollapsed) {
        // Expand all cards
        cards.forEach(card => {
            card.classList.remove('collapsed');
        });
        collapseAllText.textContent = 'Collapse All';
        collapseAllIcon.className = 'fas fa-compress-alt me-1';
        collapseAllBtn.classList.remove('btn-outline-success');
        collapseAllBtn.classList.add('btn-outline-secondary');
    } else {
        // Collapse all cards
        cards.forEach(card => {
            card.classList.add('collapsed');
        });
        collapseAllText.textContent = 'Expand All';
        collapseAllIcon.className = 'fas fa-expand-alt me-1';
        collapseAllBtn.classList.remove('btn-outline-secondary');
        collapseAllBtn.classList.add('btn-outline-success');
    }
}

function toggleCard(card) {
    // Don't toggle if clicking on interactive elements
    if (event.target.closest('.dropdown') || 
        event.target.closest('.c-switch') || 
        event.target.closest('button') ||
        event.target.closest('a') ||
        event.target.closest('input')) {
        return;
    }
    
    // Toggle the card
    card.classList.toggle('collapsed');
    
    // Update the collapse all button state
    updateCollapseAllButton();
}

function updateCollapseAllButton() {
    const cards = document.querySelectorAll('.receipt-card');
    const collapseAllBtn = document.getElementById('collapseAllBtn');
    const collapseAllText = document.getElementById('collapseAllText');
    const collapseAllIcon = collapseAllBtn.querySelector('i');
    
    const allCollapsed = Array.from(cards).every(card => card.classList.contains('collapsed'));
    const allExpanded = Array.from(cards).every(card => !card.classList.contains('collapsed'));
    
    if (allCollapsed) {
        collapseAllText.textContent = 'Expand All';
        collapseAllIcon.className = 'fas fa-expand-alt me-1';
        collapseAllBtn.classList.remove('btn-outline-secondary');
        collapseAllBtn.classList.add('btn-outline-success');
    } else if (allExpanded) {
        collapseAllText.textContent = 'Collapse All';
        collapseAllIcon.className = 'fas fa-compress-alt me-1';
        collapseAllBtn.classList.remove('btn-outline-success');
        collapseAllBtn.classList.add('btn-outline-secondary');
    } else {
        // Mixed state - some collapsed, some expanded
        collapseAllText.textContent = 'Collapse All';
        collapseAllIcon.className = 'fas fa-compress-alt me-1';
        collapseAllBtn.classList.remove('btn-outline-success');
        collapseAllBtn.classList.add('btn-outline-secondary');
    }
}

function removeFilter(filterName) {
    // Get current URL
    const currentUrl = new URL(window.location);
    
    // Remove the specific filter parameter
    currentUrl.searchParams.delete(filterName);
    
    // Redirect to the new URL
    window.location.href = currentUrl.toString();
}
</script> 