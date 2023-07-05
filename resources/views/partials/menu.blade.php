<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show" style="background: linear-gradient(86deg,#000000 0%,#05073f 100%)">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" >
            <?php echo trans('panel.site_title'); ?> 
        </a>
    </div>

    <ul class="c-sidebar-nav">
        
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>

        <li class="nav-title">الفواتير</li>

        @can('receipts_managment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/receipt-socials*") ? "c-show" : "" }} {{ request()->is("admin/receipt-social-products*") ? "c-show" : "" }} {{ request()->is("admin/receipt-clients*") ? "c-show" : "" }} {{ request()->is("admin/receipt-client-products*") ? "c-show" : "" }} {{ request()->is("admin/receipt-companies*") ? "c-show" : "" }} {{ request()->is("admin/banned-phones*") ? "c-show" : "" }}  {{ request()->is("admin/receipt-outgoings*") ? "c-show" : "" }} {{ request()->is("admin/receipt-outgoing-products*") ? "c-show" : "" }} {{ request()->is("admin/receipt-price-views*") ? "c-show" : "" }} {{ request()->is("admin/receipt-price-view-products*") ? "c-show" : "" }} {{ request()->is("admin/excel-files*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-receipt c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.receiptsManagment.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('receipt_social_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-socials.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-socials") || request()->is("admin/receipt-socials/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-facebook-square c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.receiptSocial.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('receipt_client_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-clients.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-clients") || request()->is("admin/receipt-clients/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-address-card c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.receiptClient.title') }}
                            </a>
                        </li>
                    @endcan 
                    @can('receipt_company_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-companies.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-companies") || request()->is("admin/receipt-companies/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-building c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.receiptCompany.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('receipt_outgoing_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-outgoings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-outgoings") || request()->is("admin/receipt-outgoings/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-gitter c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.receiptOutgoing.title') }}
                            </a>
                        </li>
                    @endcan 
                    @can('receipt_price_view_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-price-views.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-price-views") || request()->is("admin/receipt-price-views/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-hand-holding-usd c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.receiptPriceView.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('banned_phone_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.banned-phones.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/banned-phones") || request()->is("admin/banned-phones/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-ban c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.bannedPhone.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('excel_file_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.excel-files.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/excel-files") || request()->is("admin/excel-files/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-table c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.excelFile.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('playlist_access')
            @php
                // this is a counter to count the playlist depend on the status (design,manufacturing,prepare,shipment)
                $playlists_to_count = DB::table('view_playlist_data')
                                ->whereNotIn('playlist_status',['pending','finish'])
                                ->select('playlist_status', DB::raw('count(*) as total'))
                                ->groupBy('playlist_status')
                                ->get();
                $playlists_counter = [
                    'design' => 0,
                    'manufacturing' => 0,
                    'prepare' => 0,
                    'shipment' => 0,
                ];
                $playlists_counter_sum = 0;
                foreach($playlists_to_count as $play_raw_group){
                    $playlists_counter_sum += $play_raw_group->total;
                    $playlists_counter[$play_raw_group->playlist_status] = $play_raw_group->total;
                }
            @endphp
        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/playlists/*") ? "c-active" : "" }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="fa-fw fas fa-industry c-sidebar-nav-icon">

                </i>
                {{ trans('cruds.playlist.title') }}
                @if($playlists_counter_sum) <span class="badge bg-light-gradient text-dark ms-auto">{{ $playlists_counter_sum   }}</span> @endif
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a href="{{ route("admin.playlists.index",'design') }}" class="c-sidebar-nav-link {{ request()->is("admin/playlists/design") ? "c-active" : "" }}">
                        <i class="fa-fw fas fa-pen-nib c-sidebar-nav-icon">

                        </i>
                        {{ trans('cruds.playlist.menu.design') }} 
                        @if($playlists_counter['design']) <span class="badge bg-primary-gradient ms-auto">{{ $playlists_counter['design'] }}</span>@endif
                    </a>
                <li class="c-sidebar-nav-item">
                </li>
                    <a href="{{ route("admin.playlists.index",'manufacturing') }}" class="c-sidebar-nav-link {{ request()->is("admin/playlists/manufacturing") ? "c-active" : "" }}">
                        <i class="fa-fw fas fa-building c-sidebar-nav-icon"> 

                        </i>
                        {{ trans('cruds.playlist.menu.manufacturing') }}
                        @if($playlists_counter['manufacturing']) <span class="badge bg-warning-gradient text-dark ms-auto">{{ $playlists_counter['manufacturing'] }}</span> @endif
                    </a>
                </li>
                </li>
                    <a href="{{ route("admin.playlists.index",'prepare') }}" class="c-sidebar-nav-link {{ request()->is("admin/playlists/prepare") ? "c-active" : "" }}">
                        <i class="fa-fw fab fa-envira c-sidebar-nav-icon">

                        </i>
                        {{ trans('cruds.playlist.menu.prepare') }}
                        @if($playlists_counter['prepare']) <span class="badge bg-success-gradient  ms-auto">{{ $playlists_counter['prepare'] }}</span> @endif
                    </a>
                </li>
                </li>
                    <a href="{{ route("admin.playlists.index",'shipment') }}" class="c-sidebar-nav-link {{ request()->is("admin/playlists/shipment") ? "c-active" : "" }}">
                        <i class="fa-fw fas fa-truck c-sidebar-nav-icon">

                        </i>
                        {{ trans('cruds.playlist.menu.shipment') }}
                        @if($playlists_counter['shipment']) <span class="badge bg-danger-gradient ms-auto">{{ $playlists_counter['shipment'] }}</span> @endif
                    </a>
                </li>
            </ul>
        </li>
        @endcan
        @can('delivery_managment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/delivery-orders*") ? "c-show" : "" }} {{ request()->is("admin/deliver-men*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-industry c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.deliveryManagment.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('delivery_order_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.delivery-orders.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/delivery-orders") || request()->is("admin/delivery-orders/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-truck-loading c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.deliveryOrder.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('deliver_man_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.deliver-men.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/deliver-men") || request()->is("admin/deliver-men/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-clock c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.deliverMan.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        <li class="nav-title">الموقع</li>
        @can('product_managment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/products*") ? "c-show" : "" }} {{ request()->is("admin/categories*") ? "c-show" : "" }} {{ request()->is("admin/sub-categories*") ? "c-show" : "" }} {{ request()->is("admin/sub-sub-categories*") ? "c-show" : "" }} {{ request()->is("admin/attributes*") ? "c-show" : "" }} {{ request()->is("admin/reviews*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-dolly-flatbed c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.productManagment.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('product_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.products.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/products") || request()->is("admin/products/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-product-hunt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.product.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/categories") || request()->is("admin/categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.category.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('sub_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.sub-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/sub-categories") || request()->is("admin/sub-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-quote-left c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.subCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('sub_sub_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.sub-sub-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/sub-sub-categories") || request()->is("admin/sub-sub-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-stack-exchange c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.subSubCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('attribute_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.attributes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/attributes") || request()->is("admin/attributes/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-asterisk c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.attribute.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('review_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.reviews.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/reviews") || request()->is("admin/reviews/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-star-half-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.review.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        
        @can('order_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.orders.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/orders") || request()->is("admin/orders/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-gift c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.order.title') }}
                    <span class="badge bg-primary-gradient ms-auto">{{\App\Models\Order::where('playlist_status','pending')->count()}}</span>
                </a>
            </li>
        @endcan
        @can('mockups_managment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/designers*") ? "c-show" : "" }} {{ request()->is("admin/mockups*") ? "c-show" : "" }} {{ request()->is("admin/designes*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-pen-nib c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.mockupsManagment.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('designer_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.designers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/designers") || request()->is("admin/designers/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-edit c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.designer.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('mockup_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.mockups.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/mockups") || request()->is("admin/mockups/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-drafting-compass c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.mockup.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('designe_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.designes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/designes") || request()->is("admin/designes/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-palette c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.designe.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('frontend_setting_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/sliders*") ? "c-show" : "" }} {{ request()->is("admin/banners*") ? "c-show" : "" }} {{ request()->is("admin/home-categories*") ? "c-show" : "" }} {{ request()->is("admin/quality-responsibles*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fab fa-500px c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.frontendSetting.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('slider_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.sliders.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/sliders") || request()->is("admin/sliders/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-images c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.slider.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('banner_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.banners.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/banners") || request()->is("admin/banners/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-image c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.banner.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('home_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.home-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/home-categories") || request()->is("admin/home-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-assistive-listening-systems c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.homeCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('quality_responsible_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.quality-responsibles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/quality-responsibles") || request()->is("admin/quality-responsibles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-quora c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.qualityResponsible.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        
        <li class="nav-title">المستخدمين</li>
        @can('user_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }} {{ request()->is("admin/audit-logs*") ? "c-show" : "" }} ">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('borrows_and_subtraction_access')
                        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/employees*") ? "c-show" : "" }} {{ request()->is("admin/borrows*") ? "c-show" : "" }} {{ request()->is("admin/subtractions*") ? "c-show" : "" }}">
                            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                                <i class="fa-fw fas fa-file-invoice-dollar c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.borrowsAndSubtraction.title') }}
                            </a>
                            <ul class="c-sidebar-nav-dropdown-items">
                                @can('employee_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.employees.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/employees") || request()->is("admin/employees/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-user-astronaut c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.employee.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('borrow_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.borrows.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/borrows") || request()->is("admin/borrows/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-minus-circle c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.borrow.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('subtraction_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.subtractions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/subtractions") || request()->is("admin/subtractions/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fab fa-creative-commons-nc c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.subtraction.title') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('audit_log_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.audit-logs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.auditLog.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('customer_access')
            @php 
                $settings11 = [
                    'chart_title'           => 'last 7days customers registered',
                    'chart_type'            => 'number_block',
                    'report_type'           => 'group_by_date',
                    'model'                 => 'App\Models\Customer',
                    'group_by_field'        => 'created_at',
                    'group_by_period'       => 'day',
                    'aggregate_function'    => 'count',
                    'filter_field'          => 'created_at',
                    'filter_days'           => '7',
                    'group_by_field_format' => 'd/m/Y H:i:s',
                    'column_class'          => 'col-md-12',
                    'entries_number'        => '5',
                    'translation_key'       => 'customer',
                ];

                $settings11['total_number'] = 0;
                if (class_exists($settings11['model'])) {
                    $settings11['total_number'] = $settings11['model']::when(isset($settings11['filter_field']), function ($query) use ($settings11) {
                        if (isset($settings11['filter_days'])) {
                            return $query->where($settings11['filter_field'], '>=',
                                now()->subDays($settings11['filter_days'])->format('Y-m-d'));
                        } elseif (isset($settings11['filter_period'])) {
                            switch ($settings11['filter_period']) {
                                case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                                break;
                                case 'month': $start = date('Y-m') . '-01';
                                break;
                                case 'year': $start = date('Y') . '-01-01';
                                break;
                            }
                            if (isset($start)) {
                                return $query->where($settings11['filter_field'], '>=', $start);
                            }
                        }
                    })
                        ->{$settings11['aggregate_function'] ?? 'count'}($settings11['aggregate_field'] ?? '*');
                }
            @endphp
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.customers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/customers") || request()->is("admin/customers/*") ? "c-active" : "" }}">
                    <i class="fa-fw far fa-user c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.customer.title') }}
                    @if(number_format($settings11['total_number'])) <span class="badge bg-success-gradient text-dark ms-auto">{{ number_format($settings11['total_number']) }} جديد</span> @endif
                </a>
            </li>
        @endcan
        @can('seller_managment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/sellers*") ? "c-show" : "" }} {{ request()->is("admin/commission-requests*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.sellerManagment.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('seller_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.sellers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/sellers") || request()->is("admin/sellers/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.seller.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('commission_request_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.commission-requests.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/commission-requests") || request()->is("admin/commission-requests/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-credit-card c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.commissionRequest.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('task_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/task-statuses*") ? "c-show" : "" }} {{ request()->is("admin/task-tags*") ? "c-show" : "" }} {{ request()->is("admin/tasks*") ? "c-show" : "" }} {{ request()->is("admin/tasks-calendars*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-list c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.taskManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('task_status_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.task-statuses.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/task-statuses") || request()->is("admin/task-statuses/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-server c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.taskStatus.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('task_tag_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.task-tags.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/task-tags") || request()->is("admin/task-tags/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-server c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.taskTag.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('task_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.tasks.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/tasks") || request()->is("admin/tasks/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.task.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('tasks_calendar_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.tasks-calendars.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/tasks-calendars") || request()->is("admin/tasks-calendars/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-calendar c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.tasksCalendar.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan 


        <li class="nav-title">عام</li>
        @can('setting_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/website-settings*") ? "c-show" : "" }} {{ request()->is("admin/user-alerts*") ? "c-show" : "" }} {{ request()->is("admin/countries*") ? "c-show" : "" }} {{ request()->is("admin/socials*") ? "c-show" : "" }} {{ request()->is("admin/polices*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cog c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.setting.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('website_setting_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.website-settings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/website-settings") || request()->is("admin/website-settings/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-wrench c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.websiteSetting.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_alert_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.user-alerts.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/user-alerts") || request()->is("admin/user-alerts/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-bell c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.userAlert.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('country_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.countries.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/countries") || request()->is("admin/countries/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-globe-americas c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.country.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('social_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.socials.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/socials") || request()->is("admin/socials/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-camera-retro c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.social.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('police_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.polices.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/polices") || request()->is("admin/polices/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-secret c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.police.title') }}
                            </a>
                        </li>
                    @endcan 
                    @can('faq_management_access')
                        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/faq-categories*") ? "c-show" : "" }} {{ request()->is("admin/faq-questions*") ? "c-show" : "" }}">
                            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                                <i class="fa-fw fas fa-question c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.faqManagement.title') }}
                            </a>
                            <ul class="c-sidebar-nav-dropdown-items">
                                @can('faq_category_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.faq-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/faq-categories") || request()->is("admin/faq-categories/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.faqCategory.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('faq_question_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.faq-questions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/faq-questions") || request()->is("admin/faq-questions/*") ? "c-active" : "" }}">
                                            <i class="fa-fw far fa-question-circle c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.faqQuestion.title') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('currency_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.currencies.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/currencies") || request()->is("admin/currencies/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-hand-holding-usd c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.currency.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('contactu_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.contactus.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/contactus") || request()->is("admin/contactus/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-address-card c-sidebar-nav-icon">
            
                                </i>
                                {{ trans('cruds.contactu.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('subscribe_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.subscribes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/subscribes") || request()->is("admin/subscribes/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-envelope c-sidebar-nav-icon">
            
                                </i>
                                {{ trans('cruds.subscribe.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.systemCalendar") }}" class="c-sidebar-nav-link {{ request()->is("admin/system-calendar") || request()->is("admin/system-calendar/*") ? "c-active" : "" }}">
                <i class="c-sidebar-nav-icon fa-fw fas fa-calendar">

                </i>
                {{ trans('global.systemCalendar') }}
            </a>
        </li>
        @can('conversation_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.conversations.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/conversations") || request()->is("admin/conversations/*") ? "c-active" : "" }}">
                    <i class="fa-fw far fa-comments c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.conversation.title') }}
                    {{-- <span class="badge bg-success-gradient text-dark ms-auto">3</span> --}}
                </a>
            </li>
        @endcan 
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>