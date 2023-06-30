<header class="c-header c-header-fixed px-3" style="padding: 10px 5px;">


    <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar"
        data-class="c-sidebar-show">
        <i class="fas fa-fw fa-bars"></i>
    </button>

    <button class="c-header-toggler mfs-3 d-md-down-none" type="button" responsive="true">
        <i class="fas fa-fw fa-bars"></i>
    </button>

    <div style="width: 250px;padding: 14px 0px;">
        <select class="searchable-field form-control">

        </select>
    </div>

    <ul class="c-header-nav me-md-3 @if (app()->getLocale() == 'ar') mr-auto @else ml-auto @endif">

        @if (count(config('panel.available_languages', [])) > 1)
            <li class="c-header-nav-item dropdown me-md-3"
                style="background: #80808014; border-radius: 50%; padding: 16px 10px;">
                <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                    aria-expanded="false">
                    {{ strtoupper(app()->getLocale()) }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach (config('panel.available_languages') as $langLocale => $langName)
                        <a class="dropdown-item"
                            href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }}
                            ({{ $langName }})
                        </a>
                    @endforeach
                </div>
            </li>
        @endif
        <ul class="c-header-nav ml-auto me-md-3" style="background: #80808014; border-radius: 50%; padding: 3px 10px;">
            <li class="c-header-nav-item dropdown notifications-menu">
                <a href="#" class="c-header-nav-link" data-toggle="dropdown">
                    <i class="far fa-bell" style="font-size:20px"></i>
                    @php($alertsCount = \Auth::user()->userUserAlerts()->where('read', false)->count())
                    @if ($alertsCount > 0)
                        <span class="badge badge-warning navbar-badge">
                            {{ $alertsCount }}
                        </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    @if (count(
                            $alerts = \Auth::user()->userUserAlerts()->whereIn('type', ['public', 'private'])->withPivot('read')->limit(10)->orderBy('created_at', 'DESC')->get()) > 0)
                        @foreach ($alerts as $alert)
                            <div class="dropdown-item">
                                <a href="{{ $alert->alert_link ? $alert->alert_link : '#' }}"
                                    @if ($alert->type == 'public') style="color: red" @else style="color: black" @endif
                                    target="_blank" rel="noopener noreferrer">
                                    @if ($alert->pivot->read === 0)
                                        <strong>
                                    @endif
                                    {{ $alert->alert_text }}
                                    @if ($alert->pivot->read === 0)
                                        </strong>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center">
                            {{ trans('global.no_alerts') }}
                        </div>
                    @endif
                </div>
            </li>
        </ul>


        <li class="c-header-nav-item nav-item dropdown d-flex align-items-center">
            <a class="c-header-nav-link nav-link py-0 show" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="true" aria-expanded="false">
                <img src="{{ asset(auth()->user()->photo ? auth()->user()->photo->getUrl('thumb') : 'user.png') }}" height="50" width="50" style="border-radius: 50%"
                    alt="">
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right pt-0"
                style="position: absolute; inset: 0px 0px auto auto; margin: 0px;@if (app()->getLocale() == 'ar') transform: translate(126px, 50px);  @else transform: translate(0px, 42px); @endif">
                <div class="dropdown-header bg-light py-2">
                    <div class="fw-semibold">ShortCuts</div>
                </div> 
                    <a class="dropdown-item" href="{{ route('admin.customers.index') }}">
                        Customers <span class="badge badge-danger">{{\App\Models\Customer::count()}}</span>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.subscribes.index') }}">
                        Subscribers <span class="badge badge-info">{{\App\Models\Subscribe::count()}}</span>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.contactus.index') }}">
                        ContactUs <span class="badge badge-warning">{{\App\Models\Contactu::count()}}</span>
                    </a>
                <div class="dropdown-header bg-light py-2 dark:bg-white dark:bg-opacity-10">
                    <div class="fw-semibold">Settings</div>
                </div> 
                @can('profile_password_edit')
                    <a class="dropdown-item" href="{{ route('profile.password.edit') }}">
                        Profile <i class="fas fa-user"></i>
                    </a>
                @endcan
                    <a class="dropdown-item" href="{{ route("admin.countries.index") }}">
                        Countries <i class="fas fa-globe-americas"> </i>
                    </a> 
                    <a class="dropdown-item" href="{{ route("admin.currencies.index") }}">
                        Currencies <i class="fas fa-hand-holding-usd"> </i>
                    </a> 
                    <a class="dropdown-item" href="{{ route("admin.general-settings.index") }}">
                        Settings <i class="fas fa-cog"></i>
                    </a>
                <div class="dropdown-divider"></div> 
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        Logout <i class="fas fa-sign-out-alt"></i>
                    </a> 
            </div>
        </li>
    </ul>
    <ul class="c-header-nav me-md-3 d-sm-down-none" onclick="aside()"
        style="cursor: pointer;margin: 0px 0px 0 12px;">
        <i class="c-sidebar-nav-icon fa-fw fas fa-grip-vertical">

        </i>
    </ul>
</header>

<div class="sidebar sidebar-light sidebar-lg sidebar-end sidebar-overlaid hide" id="aside">
    <div class="sidebar-header bg-transparent p-0">
        <ul class="nav nav-underline nav-underline-primary" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab">
                    <i class="c-sidebar-nav-icon fa-fw fas fa-bars">
                    </i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#messages" role="tab">
                    <i class="c-sidebar-nav-icon fa-fw far fa-comment-dots">
                    </i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#history" role="tab">
                    <i class="c-sidebar-nav-icon fa-fw fas fa-history">
                    </i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#settings" role="tab">
                    <i class="c-sidebar-nav-icon fa-fw fas fa-cog">
                    </i>
                </a>
            </li>
        </ul>
        <button class="sidebar-close" type="button" onclick="aside()">
            <i class="c-sidebar-nav-icon fa-fw fas fa-times">

            </i>
        </button>
    </div>
    <!-- Tab panes-->
    <div class="tab-content">
        <div class="tab-pane active" id="timeline" role="tabpanel">
            <div class="list-group list-group-flush">
                <div
                    class="list-group-item border-start-4 border-start-secondary bg-light text-center fw-bold text-medium-emphasis text-uppercase small dark:bg-white dark:bg-opacity-10 dark:text-medium-emphasis">
                    مراحل التشغيل <a href="#">عرض الكل</a>
                </div>

                @if (count(
                        $alerts = \App\Models\UserAlert::where('type', 'playlist')->limit(10)->orderBy('created_at', 'DESC')->get()) > 0)
                    @foreach ($alerts as $alert)
                        <div class="list-group-item border-start-4 border-start-warning list-group-item-divider">
                            <div>{{ $alert->alert_text }}</div>
                            <small class="text-medium-emphasis me-3">
                                {{ $alert->created_at }}
                            </small>
                        </div>
                    @endforeach
                @else
                    <div class="text-center">
                        {{ trans('global.no_alerts') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="tab-pane" id="history" role="tabpanel">
            <div class="list-group list-group-flush">
                <div
                    class="list-group-item border-start-4 border-start-secondary bg-light text-center fw-bold text-medium-emphasis text-uppercase small dark:bg-white dark:bg-opacity-10 dark:text-medium-emphasis">
                    الفواتير اللتي أرسلتها <a href="#">عرض الكل</a>
                </div>

                @if (count($history = \Auth::user()->userUserAlerts()->where('type', 'history')->limit(10)->orderBy('created_at', 'DESC')->get()) > 0)
                    @foreach ($history as $alert)
                        <div class="list-group-item border-start-4 border-start-warning list-group-item-divider">
                            <div>{{ $alert->alert_text }}</div>
                            <small class="text-medium-emphasis me-3">
                                {{ $alert->created_at }}
                            </small>
                        </div>
                    @endforeach
                @else
                    <div class="text-center">
                        {{ trans('global.no_alerts') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="tab-pane p-3" id="messages" role="tabpanel">
            {{-- <div class="message">
                <div class="py-3 pb-5 me-3 float-start">
                    <div class="avatar"><img class="avatar-img" src="assets/img/avatars/7.jpg"
                            alt="user@email.com"><span class="avatar-status bg-success"></span></div>
                </div>
                <div><small class="text-medium-emphasis">Lukasz Holeczek</small><small
                        class="text-medium-emphasis float-end mt-1">1:52 PM</small></div>
                <div class="text-truncate fw-bold">Lorem ipsum dolor sit amet</div><small
                    class="text-medium-emphasis">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                    eiusmod tempor incididunt...</small>
            </div>
            <hr> --}}
            <div>Comming Soon ...</div>
        </div>
        <div class="tab-pane p-3" id="settings" role="tabpanel">
            <h6>Settings</h6>
            <div class="aside-options">
                <div class="clearfix mt-4">
                    <div class="form-check form-switch form-switch-lg">
                        <input class="form-check-input me-0" id="flexSwitchCheckDefaultLg" type="checkbox"
                            checked="">
                        <label class="form-check-label fw-semibold small" for="flexSwitchCheckDefaultLg">Option
                            1</label>
                    </div>
                </div>
                <div><small class="text-medium-emphasis">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                        do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></div>
            </div>
            <div class="aside-options">
                <div class="clearfix mt-3">
                    <div class="form-check form-switch form-switch-lg">
                        <input class="form-check-input me-0" id="flexSwitchCheckDefaultLg" type="checkbox">
                        <label class="form-check-label fw-semibold small" for="flexSwitchCheckDefaultLg">Option
                            2</label>
                    </div>
                </div>
                <div><small class="text-medium-emphasis">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                        do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></div>
            </div>
            <div class="aside-options">
                <div class="clearfix mt-3">
                    <div class="form-check form-switch form-switch-lg">
                        <input class="form-check-input me-0" id="flexSwitchCheckDefaultLg" type="checkbox">
                        <label class="form-check-label fw-semibold small" for="flexSwitchCheckDefaultLg">Option
                            3</label>
                    </div>
                </div>
            </div>
            <div class="aside-options">
                <div class="clearfix mt-3">
                    <div class="form-check form-switch form-switch-lg">
                        <input class="form-check-input me-0" id="flexSwitchCheckDefaultLg" type="checkbox"
                            checked="">
                        <label class="form-check-label fw-semibold small" for="flexSwitchCheckDefaultLg">Option
                            4</label>
                    </div>
                </div>
            </div>
            <hr>
            <h6>System Utilization</h6>
            <div>Comming Soon ....</div>
            {{-- <div class="text-uppercase mb-1 mt-4"><small><b>CPU Usage</b></small></div>
            <div class="progress progress-thin">
                <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div><small class="text-medium-emphasis">348 Processes. 1/4 Cores.</small>
            <div class="text-uppercase mb-1 mt-2"><small><b>Memory Usage</b></small></div>
            <div class="progress progress-thin">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div><small class="text-medium-emphasis">11444GB/16384MB</small>
            <div class="text-uppercase mb-1 mt-2"><small><b>SSD 1 Usage</b></small></div>
            <div class="progress progress-thin">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div><small class="text-medium-emphasis">243GB/256GB</small>
            <div class="text-uppercase mb-1 mt-2"><small><b>SSD 2 Usage</b></small></div>
            <div class="progress progress-thin">
                <div class="progress-bar bg-success" role="progressbar" style="width: 10%" aria-valuenow="10"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div><small class="text-medium-emphasis">25GB/256GB</small> --}}
        </div>
    </div>
</div>
