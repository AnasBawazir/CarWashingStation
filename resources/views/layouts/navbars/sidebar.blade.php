<nav class="navbar navbar-vertical @if(session('direction') == 'rtl') fixed-right @else fixed-left @endif
    navbar-expand-md navbar-light bg-gradient-white" id="sidenav-main">
    <div class="container-fluid">

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand pt-0" href="{{ url('admin/home') }}">

            <img src="{{ url('images/magasil.png') }}" width="400" height="200" class="navbar-brand-img" alt="">
        </a>

        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img src="{{ url('images/upload/'.auth()->user()->image) }}" alt="">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="{{ url('admin/admin_edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>

        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ url('admin/home') }}">
                            @php
                                $company_logo = App\Models\Setting::find(1)->company_logo;
                            @endphp
{{--                            <img src="{{ url('images/upload/'.$company_logo) }}" width="400" height="200">--}}
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Navigation -->
            <ul class="navbar-nav">

                @can('admin_dashboard')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'home' ? 'active_link' : '' }}" href="{{ url('admin/home') }}">
                        <i class="fas fa-columns text-primary"></i>
                        <span class="nav-link-text">{{__('Dashboard')}}</span>
                    </a>
                </li>
                @endcan

                    @can('coworker_dashboard')
                        <li class="nav-item">
                            <a class="nav-link {{ $activePage == 'dashboard' ? ' active_link' : '' }}" href="{{ url('coworker/coworker_home') }}">
                                <i class="fas fa-columns text-success"></i>
                                <span class="nav-link-text">{{__('Dashboard')}}</span>
                            </a>
                        </li>
                    @endcan

                @can('category_access')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'category' ? ' active_link' : '' }}" href="{{ url('admin/category') }}">
                        <i class="fas fa-paste text-danger"></i>
                        <span class="nav-link-text">{{__('category')}}</span>
                    </a>
                </li>
                @endcan



                @can('station_access')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'stations' ? ' active_link' : '' }}" href="{{ url('admin/stations') }}">
                        <i class="fas fa-home text-info"></i>
                        <span class="nav-link-text">{{__('Stations')}}</span>
                    </a>
                </li>
                @endcan


                @can('coworker_access')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'coworkers' ? ' active_link' : '' }}" href="{{ url('admin/coworkers') }}">
                        <i class="fas fa-car-side text-warning"></i>
                        <span class="nav-link-text">{{__('Co-workers')}}</span>
                    </a>
                </li>
                @endcan

                @can('service_access')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'service' ? ' active_link' : '' }}" href="{{ url('admin/service') }}">
                        <i class="fas fa-concierge-bell text-dark"></i>
                        <span class="nav-link-text">{{__('Service')}}</span>
                    </a>
                </li>
                @endcan

                @can('offer_access')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'offer' ? ' active_link' : '' }}" href="{{ url('admin/offer') }}">
                        <i class="fas fa-percentage text-warning"></i>
                        <span class="nav-link-text">{{__('Offer')}}</span>
                    </a>
                </li>
                @endcan

                @can('admin_appointment_access')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'appointment' ? ' active_link' : '' }}" href="{{ url('admin/appointment') }}">
                        <i class="ni ni-bag-17 text-success"></i>
                        <span class="nav-link-text">{{__('appointment')}}</span>
                    </a>
                </li>
                @endcan

                @can('admin_appointment_calender')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'calendar' ? ' active_link' : '' }}" href="{{ url('admin/calendar') }}">
                        <i class="ni ni-calendar-grid-58 text-danger"></i>
                        <span class="nav-link-text">{{__('Calendar')}}</span>
                    </a>
                </li>
                @endcan

                @can('admin_custom_notification')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'notification' ? ' active_link' : '' }}" href="{{ url('admin/notification') }}">
                        <i class="fas fa-bell text-primary"></i>
                        <span class="nav-link-text">{{__('Notification')}}</span>
                    </a>
                </li>
                @endcan

{{--                @can('notification_template')--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ $activePage == 'notification_template' ? ' active_link' : '' }}" href="{{ url('admin/notification_template') }}">--}}
{{--                        <i class="fas fa-sticky-note text-danger"></i>--}}
{{--                        <span class="nav-link-text">{{__('Notification template')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                @endcan--}}

                @can('user_access')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'user' ? ' active_link' : '' }}" href="{{ url('admin/user') }}">
                        <i class="fas fa-users text-info"></i>
                        <span class="nav-link-text">{{__('user')}}</span>
                    </a>
                </li>
                @endcan

{{--                @can('language_access')--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ $activePage == 'language' ? ' active_link' : '' }}" href="{{ url('admin/language') }}">--}}
{{--                        <i class="fas fa-language text-success"></i>--}}
{{--                        <span class="nav-link-text">{{__('language')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                @endcan--}}

{{--                @can('faq_access')--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ $activePage == 'faq' ? ' active_link' : '' }}" href="{{ url('admin/faq') }}">--}}
{{--                    <i class="ni ni-folder-17 text-primary"></i>--}}
{{--                    <span class="nav-link-text">{{__('Faqs')}}</span></a>--}}
{{--                </li>--}}
{{--                @endcan--}}

                @can('role_access')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'role' ? ' active_link' : '' }}" href="{{ url('admin/role') }}">
                        <i class="fas fa-user-tag text-danger"></i>
                        <span class="nav-link-text">{{__('Role and permission')}}</span></a>
                </li>
                @endcan

{{--                @can('admin_setting')--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ $activePage == 'setting' ? ' active_link' : '' }}" href="{{ url('admin/setting') }}">--}}
{{--                        <i class="fas fa-cog text-success"></i>--}}
{{--                        <span class="nav-link-text">{{__('setting')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                @endcan--}}

                {{-- Employee --}}


                @can('coworker_appointment')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'appointment' ? ' active_link' : '' }}" href="{{ url('coworker/appointment') }}">
                        <i class="far fa-calendar-check text-dark"></i>
                        <span class="nav-link-text">{{__('Coworker Appointment')}}</span>
                    </a>
                </li>
                @endcan

                @can('coworker_review')
                <li class="nav-item">
                    <a class="nav-link {{ $activePage == 'worker_review' ? ' active_link' : '' }}" href="{{ url('coworker/worker_review') }}">
                        <i class="fas fa-file-pdf text-info"></i>
                        <span class="nav-link-text">{{__('Coworker review')}}</span>
                    </a>
                </li>
                @endcan

{{--                @can('coworker_portfolio_access')--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ $activePage == 'portfolio' ? ' active_link' : '' }}" href="{{ url('coworker/portfolio') }}">--}}
{{--                        <i class="far fa-images text-danger"></i>--}}
{{--                        <span class="nav-link-text">{{__('Coworker PortFolio')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                @endcan--}}

                    @can('station_dashboard')
                        <li class="nav-item">
                            <a class="nav-link {{ $activePage == 'dashboard' ? ' active_link' : '' }}" href="{{ url('stations/station_home') }}">
                                <i class="fas fa-columns text-success"></i>
                                <span class="nav-link-text">{{__('Dashboard')}}</span>
                            </a>
                        </li>
                    @endcan
                    @can('station_appointment')
                        <li class="nav-item">
                            <a class="nav-link {{ $activePage == 'appointment' ? ' active_link' : '' }}" href="{{ url('stations/appointment') }}">
                                <i class="far fa-calendar-check text-dark"></i>
                                <span class="nav-link-text">{{__('Station Appointment')}}</span>
                            </a>
                        </li>
                    @endcan

                    @can('station_review')
                        <li class="nav-item">
                            <a class="nav-link {{ $activePage == 'station_review' ? ' active_link' : '' }}" href="{{ url('stations/station_review') }}">
                                <i class="fas fa-file-pdf text-info"></i>
                                <span class="nav-link-text">{{__('Station review')}}</span>
                            </a>
                        </li>
                    @endcan
            </ul>
        </div>
    </div>
</nav>
