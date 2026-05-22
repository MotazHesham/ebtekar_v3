@php
    $user = auth()->user();
    $type = $user->user_type;
@endphp

<li class="c-sidebar-nav-item">
    <a href="{{ route('admin.shipping.dashboard.home') }}"
        class="c-sidebar-nav-link {{ request()->is('admin/shipping/dashboard*') ? 'c-active' : '' }}">
        <i class="fa-fw fas fa-tachometer-alt c-sidebar-nav-icon"></i>
        {{ __('reports::actions.dashboard') }}
    </a>
</li>


@can('delivery_order_access')
    <li class="c-sidebar-nav-item">
        <a href="{{ route('admin.delivery-orders.index') }}"
            class="c-sidebar-nav-link {{ request()->is('admin/delivery-orders*') ? 'c-active' : '' }}">
            <i class="fa-fw fas fa-truck-loading c-sidebar-nav-icon"></i>
            {{ __('reports::actions.my_shipments') }}
        </a>
    </li>
@endcan
@can('delivery_scan_receive')
    <li class="c-sidebar-nav-item">
        <a href="{{ route('admin.tracking.scan.receive.page') }}"
            class="c-sidebar-nav-link {{ request()->is('admin/tracking/scan/receive*') ? 'c-active' : '' }}">
            <i class="fa-fw fas fa-barcode c-sidebar-nav-icon"></i>
            {{ __('tracking::scan.menu_receive') }}
        </a>
    </li>
@endcan

@can('delivery_order_access')
    <li class="c-sidebar-nav-item">
        <a href="{{ route('admin.delivery-orders.index') }}"
            class="c-sidebar-nav-link {{ request()->is('admin/delivery-orders*') ? 'c-active' : '' }}">
            <i class="fa-fw fas fa-motorcycle c-sidebar-nav-icon"></i>
            {{ __('reports::actions.my_deliveries') }}
        </a>
    </li>
@endcan

@can('delivery_order_access')
    <li class="c-sidebar-nav-item">
        <a href="{{ route('admin.delivery-orders.index') }}"
            class="c-sidebar-nav-link {{ request()->is('admin/delivery-orders*') ? 'c-active' : '' }}">
            <i class="fa-fw fas fa-truck-loading c-sidebar-nav-icon"></i>
            {{ __('cruds.deliveryOrder.title') }}
        </a>
    </li>
@endcan
@can('delivery_assign_courier')
    <li class="c-sidebar-nav-item">
        <a href="{{ route('admin.dispatch.index') }}"
            class="c-sidebar-nav-link {{ request()->is('admin/dispatch*') ? 'c-active' : '' }}">
            <i class="fa-fw fas fa-random c-sidebar-nav-icon"></i>
            {{ __('dispatch::actions.menu') }}
        </a>
    </li>
@endcan
@can('delivery_settlement_access')
    <li class="c-sidebar-nav-item">
        <a href="{{ route('admin.settlements.index') }}"
            class="c-sidebar-nav-link {{ request()->is('admin/settlements*') ? 'c-active' : '' }}">
            <i class="fa-fw fas fa-cash-register c-sidebar-nav-icon"></i>
            {{ __('settlement::actions.menu') }}
        </a>
    </li>
@endcan
@can('delivery_return_access')
    <li class="c-sidebar-nav-item">
        <a href="{{ route('admin.returns.index') }}"
            class="c-sidebar-nav-link {{ request()->is('admin/returns*') ? 'c-active' : '' }}">
            <i class="fa-fw fas fa-undo c-sidebar-nav-icon"></i>
            {{ __('returns::actions.menu') }}
        </a>
    </li>
@endcan
