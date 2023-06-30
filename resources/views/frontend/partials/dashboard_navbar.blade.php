<div class="col-lg-3">
    <div class="account-sidebar"><a class="popup-btn">حسابي</a></div>
    <div class="dashboard-left">
        <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-right"
                    aria-hidden="true"></i> العودة</span></div>
        <div class="block-content ">
            <ul>
                <li class="active"><a href="{{ route('frontend.dashboard') }}">بيانات الحساب</a></li>
                <li><a href="{{ route('frontend.orders') }}">طلباتي</a></li>
                <li><a href="{{ route('frontend.wishlist') }}">قائمة الامنيات</a></li>
                <li><a href="{{ route('frontend.dashboard') }}">تغيير كلمة المرور</a></li>
                <li class="last">
                    <a  href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        تسجيل الخروج
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
