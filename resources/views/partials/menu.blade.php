<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li>
            <select class="searchable-field form-control">

            </select>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
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
        @can('receipts_managment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/receipt-socials*") ? "c-show" : "" }} {{ request()->is("admin/receipt-social-products*") ? "c-show" : "" }} {{ request()->is("admin/receipt-clients*") ? "c-show" : "" }} {{ request()->is("admin/receipt-client-products*") ? "c-show" : "" }} {{ request()->is("admin/receipt-companies*") ? "c-show" : "" }} {{ request()->is("admin/banned-phones*") ? "c-show" : "" }} {{ request()->is("admin/orders*") ? "c-show" : "" }} {{ request()->is("admin/receipt-outgoings*") ? "c-show" : "" }} {{ request()->is("admin/receipt-outgoing-products*") ? "c-show" : "" }} {{ request()->is("admin/receipt-price-views*") ? "c-show" : "" }} {{ request()->is("admin/receipt-price-view-products*") ? "c-show" : "" }} {{ request()->is("admin/excel-files*") ? "c-show" : "" }}">
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
                    @can('receipt_social_product_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-social-products.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-social-products") || request()->is("admin/receipt-social-products/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.receiptSocialProduct.title') }}
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
                    @can('receipt_client_product_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-client-products.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-client-products") || request()->is("admin/receipt-client-products/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.receiptClientProduct.title') }}
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
                    @can('banned_phone_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.banned-phones.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/banned-phones") || request()->is("admin/banned-phones/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-ban c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.bannedPhone.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('order_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.orders.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/orders") || request()->is("admin/orders/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-gift c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.order.title') }}
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
                    @can('receipt_outgoing_product_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-outgoing-products.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-outgoing-products") || request()->is("admin/receipt-outgoing-products/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.receiptOutgoingProduct.title') }}
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
                    @can('receipt_price_view_product_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.receipt-price-view-products.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/receipt-price-view-products") || request()->is("admin/receipt-price-view-products/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.receiptPriceViewProduct.title') }}
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
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.playlists.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/playlists") || request()->is("admin/playlists/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-align-center c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.playlist.title') }}
                </a>
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
        @can('customer_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.customers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/customers") || request()->is("admin/customers/*") ? "c-active" : "" }}">
                    <i class="fa-fw far fa-user c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.customer.title') }}
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
        @can('conversation_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.conversations.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/conversations") || request()->is("admin/conversations/*") ? "c-active" : "" }}">
                    <i class="fa-fw far fa-comments c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.conversation.title') }}
                </a>
            </li>
        @endcan
        @can('setting_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/general-settings*") ? "c-show" : "" }} {{ request()->is("admin/user-alerts*") ? "c-show" : "" }} {{ request()->is("admin/countries*") ? "c-show" : "" }} {{ request()->is("admin/socials*") ? "c-show" : "" }} {{ request()->is("admin/polices*") ? "c-show" : "" }} {{ request()->is("admin/seo-settings*") ? "c-show" : "" }} {{ request()->is("admin/*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-ellipsis-h c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.setting.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('general_setting_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.general-settings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/general-settings") || request()->is("admin/general-settings/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cog c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.generalSetting.title') }}
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
                    @can('seo_setting_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.seo-settings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/seo-settings") || request()->is("admin/seo-settings/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-search c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.seoSetting.title') }}
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
        @can('admin_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.admins.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/admins") || request()->is("admin/admins/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-user-check c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.admin.title') }}
                </a>
            </li>
        @endcan
        @can('user_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }} {{ request()->is("admin/audit-logs*") ? "c-show" : "" }} {{ request()->is("admin/*") ? "c-show" : "" }}">
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
                    @can('audit_log_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.audit-logs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.auditLog.title') }}
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
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.systemCalendar") }}" class="c-sidebar-nav-link {{ request()->is("admin/system-calendar") || request()->is("admin/system-calendar/*") ? "c-active" : "" }}">
                <i class="c-sidebar-nav-icon fa-fw fas fa-calendar">

                </i>
                {{ trans('global.systemCalendar') }}
            </a>
        </li>
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>