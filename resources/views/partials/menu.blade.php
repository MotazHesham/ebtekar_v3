<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show" @if (app()->isLocal()) style="background: linear-gradient(270deg,#1116ff 0%,#5b3771 100%);" @else style="background: linear-gradient(86deg,#000000 0%,#05073f 100%)" @endif>

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" >
            <?php echo __('panel.site_title'); ?> 
        </a>
    </div>

    <ul class="c-sidebar-nav">
        
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ __('global.dashboard') }}
            </a>
        </li>

        <li class="nav-title">الفواتير</li>

        @can('receipts_managment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/receipt-socials*") ? "c-show" : "" }} {{ request()->is("admin/receipt-social-products*") ? "c-show" : "" }} {{ request()->is("admin/receipt-clients*") ? "c-show" : "" }} {{ request()->is("admin/receipt-client-products*") ? "c-show" : "" }} {{ request()->is("admin/receipt-companies*") ? "c-show" : "" }} {{ request()->is("admin/banned-phones*") ? "c-show" : "" }}  {{ request()->is("admin/receipt-outgoings*") ? "c-show" : "" }} {{ request()->is("admin/receipt-outgoing-products*") ? "c-show" : "" }} {{ request()->is("admin/receipt-price-views*") ? "c-show" : "" }} {{ request()->is("admin/receipt-price-view-products*") ? "c-show" : "" }} {{ request()->is("admin/excel-files*") ? "c-show" : "" }}   {{ request()->is("admin/r-clients*") ? "c-show" : "" }} {{ request()->is("admin/r-branches*") ? "c-show" : "" }} {{ request()->is("admin/receipt-branches*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-receipt c-sidebar-nav-icon">

                    </i>
                    {{ __('cruds.receiptsManagment.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('receipt_social_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-socials.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-socials") || request()->is("admin/receipt-socials/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-facebook-square c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.receiptSocial.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('receipt_client_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-clients.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-clients") || request()->is("admin/receipt-clients/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-address-card c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.receiptClient.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('receipt_branch_managment_access')
                        <li class="c-sidebar-nav-dropdown  {{ request()->is("admin/r-clients*") ? "c-show" : "" }} {{ request()->is("admin/r-branches*") ? "c-show" : "" }} {{ request()->is("admin/receipt-branches*") ? "c-show" : "" }}">
                            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                                <i class="fa-fw fas fa-align-right c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.receiptBranchManagment.title') }} 
                            </a>
                            <ul class="c-sidebar-nav-dropdown-items">
                                @can('r_client_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.r-clients.index",['type' => 'expense']) }}" class="c-sidebar-nav-link {{ request()->is("admin/r-clients") || request()->is("admin/r-clients/*") ? "c-active" : "" }}">
                                            <i class="fa-fw far fa-building c-sidebar-nav-icon">

                                            </i>
                                            {{ __('cruds.rClient.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('r_branch_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.r-branches.index",['type' => 'expense']) }}" class="c-sidebar-nav-link {{ request()->is("admin/r-branches") || request()->is("admin/r-branches/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-code-branch c-sidebar-nav-icon">

                                            </i>
                                            {{ __('cruds.rBranch.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('receipt_branch_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.receipt-branches.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-branches") || request()->is("admin/receipt-branches/*") ? "c-active" : "" }}">
                                            <i class="fa-fw far fa-address-card c-sidebar-nav-icon">

                                            </i>
                                            {{ __('cruds.receiptBranch.title') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('receipt_company_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-companies.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-companies") || request()->is("admin/receipt-companies/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-building c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.receiptCompany.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('receipt_outgoing_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-outgoings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-outgoings") || request()->is("admin/receipt-outgoings/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-gitter c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.receiptOutgoing.title') }}
                            </a>
                        </li>
                    @endcan 
                    @can('receipt_price_view_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-price-views.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-price-views") || request()->is("admin/receipt-price-views/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-hand-holding-usd c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.receiptPriceView.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('banned_phone_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.banned-phones.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/banned-phones") || request()->is("admin/banned-phones/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-ban c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.bannedPhone.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('excel_file_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.excel-files.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/excel-files") || request()->is("admin/excel-files/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-table c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.excelFile.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('playlist_access') 
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/playlists/*") ? "c-active" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-industry c-sidebar-nav-icon">

                    </i>
                    {{ __('cruds.playlist.title') }}
                    <span class="badge bg-light-gradient text-dark ms-auto playlist-counters" id="playlist-counter-total" onmouseover="playlistCounters(this)"><i class="far fa-eye"></i></span>
                </a>
                <ul class="c-sidebar-nav-dropdown-items"> 
                    @can('playlist_design')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.playlists.index",'design') }}" class="c-sidebar-nav-link {{ request()->is("admin/playlists/design") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-pen-nib c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.playlist.menu.design') }} 
                                <span class="badge bg-primary-gradient ms-auto playlist-counters" id="playlist-counter-design" onmouseover="playlistCounters(this)"><i class="far fa-eye"></i></span>
                            </a>
                        </li>
                    @endcan
                    @can('playlist_manufacturing')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.playlists.index",'manufacturing') }}" class="c-sidebar-nav-link {{ request()->is("admin/playlists/manufacturing") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-building c-sidebar-nav-icon"> 

                                </i>
                                {{ __('cruds.playlist.menu.manufacturing') }}
                                <span class="badge bg-warning-gradient text-dark ms-auto playlist-counters" id="playlist-counter-manufacturing" onmouseover="playlistCounters(this)"><i class="far fa-eye"></i></span> 
                            </a>
                        </li>
                    @endcan
                    @can('playlist_prepare')
                        <li class="c-sidebar-nav-item"> 
                            <a href="{{ route("admin.playlists.index",'prepare') }}" class="c-sidebar-nav-link {{ request()->is("admin/playlists/prepare") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-envira c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.playlist.menu.prepare') }}
                                <span class="badge bg-success-gradient  ms-auto playlist-counters" id="playlist-counter-prepare" onmouseover="playlistCounters(this)"><i class="far fa-eye"></i></span> 
                            </a>
                        </li>
                    @endcan
                    @can('playlist_shipment')
                        <li class="c-sidebar-nav-item"> 
                            <a href="{{ route("admin.playlists.index",'shipment') }}" class="c-sidebar-nav-link {{ request()->is("admin/playlists/shipment") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-truck c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.playlist.menu.shipment') }}
                                <span class="badge bg-danger-gradient ms-auto playlist-counters" id="playlist-counter-shipment" onmouseover="playlistCounters(this)"><i class="far fa-eye"></i></span> 
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('delivery_managment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/delivery-orders*") ? "c-show" : "" }} {{ request()->is("admin/deliver-men*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-industry c-sidebar-nav-icon">

                    </i>
                    {{ __('cruds.deliveryManagment.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('delivery_order_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.delivery-orders.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/delivery-orders") || request()->is("admin/delivery-orders/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-truck-loading c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.deliveryOrder.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('deliver_man_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.deliver-men.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/deliver-men") || request()->is("admin/deliver-men/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-clock c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.deliverMan.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.egyptexpress-airway-bills.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/egyptexpress-airway-bills") || request()->is("admin/egyptexpress-airway-bills/*") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-shipping-fast c-sidebar-nav-icon">

                </i>
                {{ __('cruds.egyptexpressAirwayBill.title') }}
            </a>
        </li>

        <li class="nav-title">الموقع</li>
        @can('product_managment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/products*") ? "c-show" : "" }} {{ request()->is("admin/categories*") ? "c-show" : "" }} {{ request()->is("admin/sub-categories*") ? "c-show" : "" }} {{ request()->is("admin/sub-sub-categories*") ? "c-show" : "" }} {{ request()->is("admin/attributes*") ? "c-show" : "" }} {{ request()->is("admin/reviews*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-dolly-flatbed c-sidebar-nav-icon">

                    </i>
                    {{ __('cruds.productManagment.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('product_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.products.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/products") || request()->is("admin/products/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-product-hunt c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.product.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/categories") || request()->is("admin/categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.category.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('sub_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.sub-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/sub-categories") || request()->is("admin/sub-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-quote-left c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.subCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('sub_sub_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.sub-sub-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/sub-sub-categories") || request()->is("admin/sub-sub-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-stack-exchange c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.subSubCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('attribute_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.attributes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/attributes") || request()->is("admin/attributes/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-asterisk c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.attribute.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('review_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.reviews.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/reviews") || request()->is("admin/reviews/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-star-half-alt c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.review.title') }}
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
                    {{ __('cruds.order.title') }} 
                </a>
            </li>
        @endcan
        @can('mockups_managment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/designers*") ? "c-show" : "" }} {{ request()->is("admin/mockups*") ? "c-show" : "" }} {{ request()->is("admin/designs*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-pen-nib c-sidebar-nav-icon">

                    </i>
                    {{ __('cruds.mockupsManagment.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('designer_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.designers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/designers") || request()->is("admin/designers/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-edit c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.designer.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('mockup_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.mockups.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/mockups") || request()->is("admin/mockups/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-drafting-compass c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.mockup.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('design_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.designs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/designs") || request()->is("admin/designs/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-palette c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.design.title') }}
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
                    {{ __('cruds.frontendSetting.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('slider_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.sliders.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/sliders") || request()->is("admin/sliders/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-images c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.slider.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('banner_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.banners.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/banners") || request()->is("admin/banners/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-image c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.banner.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('home_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.home-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/home-categories") || request()->is("admin/home-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-assistive-listening-systems c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.homeCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('quality_responsible_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.quality-responsibles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/quality-responsibles") || request()->is("admin/quality-responsibles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-quora c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.qualityResponsible.title') }}
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
                    {{ __('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items"> 
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_alert_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.user-alerts.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/user-alerts") || request()->is("admin/user-alerts/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-bell c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.userAlert.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('employee_managment_access')
                        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/employees*") ? "c-show" : "" }} {{ request()->is("admin/financial-categories*") ? "c-show" : "" }} {{ request()->is("admin/employee-financials*") ? "c-show" : "" }}">
                            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                                <i class="fa-fw fas fa-file-invoice-dollar c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.employeeManagmentAccess.title') }}
                            </a>
                            <ul class="c-sidebar-nav-dropdown-items">
                                @can('employee_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.employees.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/employees") || request()->is("admin/employees/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-user-astronaut c-sidebar-nav-icon">

                                            </i>
                                            {{ __('cruds.employee.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('financial_category_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.financial-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/financial-categories") || request()->is("admin/financial-categories/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-braille c-sidebar-nav-icon">

                                            </i>
                                            {{ __('cruds.financialCategory.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('employee_financial_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.employee-financials.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/employee-financials") || request()->is("admin/employee-financials/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-dollar-sign c-sidebar-nav-icon">

                                            </i>
                                            {{ __('cruds.employeeFinancial.title') }}
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
                                {{ __('cruds.auditLog.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('customer_access') 
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.customers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/customers") || request()->is("admin/customers/*") ? "c-active" : "" }}">
                    <i class="fa-fw far fa-user c-sidebar-nav-icon">

                    </i>
                    {{ __('cruds.customer.title') }} 
                </a>
            </li>
        @endcan
        @can('seller_managment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/sellers*") ? "c-show" : "" }} {{ request()->is("admin/commission-requests*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                    </i>
                    {{ __('cruds.sellerManagment.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('seller_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.sellers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/sellers") || request()->is("admin/sellers/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.seller.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('commission_request_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.commission-requests.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/commission-requests") || request()->is("admin/commission-requests/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-credit-card c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.commissionRequest.title') }}
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
                    {{ __('cruds.taskManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('task_status_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.task-statuses.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/task-statuses") || request()->is("admin/task-statuses/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-server c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.taskStatus.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('task_tag_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.task-tags.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/task-tags") || request()->is("admin/task-tags/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-server c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.taskTag.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('task_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.tasks.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/tasks") || request()->is("admin/tasks/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.task.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('tasks_calendar_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.tasks-calendars.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/tasks-calendars") || request()->is("admin/tasks-calendars/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-calendar c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.tasksCalendar.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan 


        <li class="nav-title">عام</li>
        @can('material_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.materials.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/materials") || request()->is("admin/materials/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-feather-alt c-sidebar-nav-icon">

                    </i>
                    {{ __('cruds.material.title') }}
                </a>
            </li>
        @endcan
        @can('expense_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/expense-categories*") ? "c-show" : "" }} {{ request()->is("admin/income-categories*") ? "c-show" : "" }} {{ request()->is("admin/expenses*") ? "c-show" : "" }} {{ request()->is("admin/incomes*") ? "c-show" : "" }} {{ request()->is("admin/expense-reports*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-money-bill c-sidebar-nav-icon">

                    </i>
                    {{ __('cruds.expenseManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('expense_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.expense-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/expense-categories") || request()->is("admin/expense-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-list c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.expenseCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('income_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.income-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/income-categories") || request()->is("admin/income-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-list c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.incomeCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('expense_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.expenses.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/expenses") || request()->is("admin/expenses/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-arrow-circle-right c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.expense.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('income_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.incomes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/incomes") || request()->is("admin/incomes/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-arrow-circle-right c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.income.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('expense_report_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.expense-reports.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/expense-reports") || request()->is("admin/expense-reports/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-chart-line c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.expenseReport.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan 
        @can('setting_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/website-settings*") ? "c-show" : "" }} {{ request()->is("admin/subscribes*") ? "c-show" : "" }} {{ request()->is("admin/contactus*") ? "c-show" : "" }} {{ request()->is("admin/currencies*") ? "c-show" : "" }} {{ request()->is("admin/faq-questions*") ? "c-show" : "" }} {{ request()->is("admin/countries*") ? "c-show" : "" }} {{ request()->is("admin/socials*") ? "c-show" : "" }} {{ request()->is("admin/polices*") ? "c-show" : "" }} {{ request()->is("admin/financial-accounts*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cog c-sidebar-nav-icon">

                    </i>
                    {{ __('cruds.setting.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('website_setting_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.website-settings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/website-settings") || request()->is("admin/website-settings/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-wrench c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.websiteSetting.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('financial_account_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.financial-accounts.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/financial-accounts") || request()->is("admin/financial-accounts/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-hand-holding-usd c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.financialAccount.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('country_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.countries.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/countries") || request()->is("admin/countries/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-globe-americas c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.country.title') }}
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.zones.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/zones") || request()->is("admin/zones/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-globe-americas c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.zone.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('social_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.socials.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/socials") || request()->is("admin/socials/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-camera-retro c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.social.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('police_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.polices.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/polices") || request()->is("admin/polices/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-secret c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.police.title') }}
                            </a>
                        </li>
                    @endcan 
                    @can('faq_management_access')
                        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/faq-categories*") ? "c-show" : "" }} {{ request()->is("admin/faq-questions*") ? "c-show" : "" }}">
                            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                                <i class="fa-fw fas fa-question c-sidebar-nav-icon">

                                </i>
                                {{ __('cruds.faqManagement.title') }}
                            </a>
                            <ul class="c-sidebar-nav-dropdown-items">
                                @can('faq_category_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.faq-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/faq-categories") || request()->is("admin/faq-categories/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                            </i>
                                            {{ __('cruds.faqCategory.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('faq_question_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.faq-questions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/faq-questions") || request()->is("admin/faq-questions/*") ? "c-active" : "" }}">
                                            <i class="fa-fw far fa-question-circle c-sidebar-nav-icon">

                                            </i>
                                            {{ __('cruds.faqQuestion.title') }}
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
                                {{ __('cruds.currency.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('contactu_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.contactus.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/contactus") || request()->is("admin/contactus/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-address-card c-sidebar-nav-icon">
            
                                </i>
                                {{ __('cruds.contactu.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('subscribe_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.subscribes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/subscribes") || request()->is("admin/subscribes/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-envelope c-sidebar-nav-icon">
            
                                </i>
                                {{ __('cruds.subscribe.title') }}
                            </a>
                        </li>
                    @endcan
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.qrs.generator") }}" class="c-sidebar-nav-link {{ request()->is("admin/qrs/generator") || request()->is("admin/qrs/generator/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-qrcode c-sidebar-nav-icon">
        
                            </i>
                            Qr Generator
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.systemCalendar") }}" class="c-sidebar-nav-link {{ request()->is("admin/system-calendar") || request()->is("admin/system-calendar/*") ? "c-active" : "" }}">
                <i class="c-sidebar-nav-icon fa-fw fas fa-calendar">

                </i>
                {{ __('global.systemCalendar') }}
            </a>
        </li>
        @can('conversation_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.conversations.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/conversations") || request()->is("admin/conversations/*") ? "c-active" : "" }}">
                    <i class="fa-fw far fa-comments c-sidebar-nav-icon">

                    </i>
                    {{ __('cruds.conversation.title') }}
                    {{-- <span class="badge bg-success-gradient text-dark ms-auto">3</span> --}}
                </a>
            </li>
        @endcan 
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ __('global.logout') }}
            </a>
        </li>
    </ul>

</div>