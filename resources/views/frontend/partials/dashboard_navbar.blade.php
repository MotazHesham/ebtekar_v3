<div class="col-lg-3">
    <div class="account-sidebar"><a class="popup-btn">{{ __('frontend.side_nav.profile') }}</a></div>
    <div class="dashboard-left">
        <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-right"
                    aria-hidden="true"></i> {{ __('frontend.side_nav.back') }}</span></div>
        <div class="block-content ">
            <ul>
                <li ><a href="{{ route('frontend.dashboard') }}">  {{ __('frontend.side_nav.info') }}</a></li>
                <li><a href="{{ route('frontend.orders') }}">{{ __('frontend.side_nav.orders') }}</a></li>
                @if( auth()->check() && auth()->user()->user_type == 'seller')
                    <li><a href="{{ route('frontend.orders.commission_requests') }}">  {{ __('frontend.side_nav.commission_request') }}</a></li>
                @endif
                @if(auth()->check() && auth()->user()->user_type == 'designer')
                    <li><a href="{{ route('frontend.mockups.categories') }}">  {{ __('frontend.side_nav.start_designs') }}</a></li>
                    <li><a href="{{ route('frontend.designs.index') }}">{{ __('frontend.side_nav.designs') }}</a></li>
                @endif
                <li><a href="{{ route('frontend.wishlist') }}">  {{ __('frontend.side_nav.wishlist') }}</a></li>
                <li><a href="{{ route('frontend.dashboard') }}">    {{ __('frontend.side_nav.change_password') }}</a></li>
                <li class="last">
                    <a  href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        {{ __('frontend.side_nav.logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
