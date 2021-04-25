<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
    <div class="container-fluid">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Navbar links -->
        <ul class="navbar-nav align-items-center  ml-md-auto ">
          <li class="nav-item d-xl-none">
            <!-- Sidenav toggler -->
            <div class="pr-3 sidenav-toggler sidenav-toggler-dark active" data-action="sidenav-pin" data-target="#sidenav-main">
              <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
              </div>
            </div>
          </li>
{{--        @if(Auth::user()->load('roles')->roles->contains('name', 'admin'))--}}
{{--            <?php--}}
{{--                $langs = \App\Models\Language::where('status',1)->get();--}}
{{--                $icon = \App\Models\Language::where('name',session('locale'))->first();--}}
{{--                if($icon)--}}
{{--                {--}}
{{--                    $lang_image="/images/upload/".$icon->image;--}}
{{--                }--}}
{{--                else--}}
{{--                {--}}
{{--                    $lang_image="/images/upload/usa.png";--}}
{{--                }--}}
{{--            ?>--}}
{{--            <ul class="navbar-nav align-items-center  ml-auto ml-md-0 flag-ul">--}}
{{--                <li class="nav-item dropdown rtl-flag">--}}
{{--                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"--}}
{{--                        aria-expanded="false">--}}
{{--                        <div class="media align-items-center">--}}
{{--                            <span class="avatar avatar-sm">--}}
{{--                                <img class="small_round flag" src="{{asset($lang_image)}}">--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-flag ">--}}
{{--                        <div class="dropdown-header noti-title">--}}
{{--                            <h6 class="text-overflow m-0">{{__('Language')}}</h6>--}}
{{--                        </div>--}}
{{--                        @foreach ($langs as $lang)--}}
{{--                        <a href="{{url('/admin/change_language/'.$lang->name)}}" class="dropdown-item">--}}
{{--                            <span class="avatar avatar-sm">--}}
{{--                                <img class="small_round flag" src="{{asset('images/upload/'.$lang->image)}}">--}}
{{--                            </span>--}}
{{--                            <span>{{$lang->name}}</span>--}}
{{--                        </a>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        @endif--}}
        @if(Auth::user()->load('roles')->roles->contains('name', 'admin'))
            <ul class="navbar-nav align-items-center  ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img src="{{ url('images/upload/'.auth()->user()->image) }}" alt="">
                        </span>
                        <div class="media-body  ml-2  d-none d-lg-block">
                        <span class="mb-0 text-sm  font-weight-bold">{{auth()->user()->name}}</span>
                        </div>
                    </div>
                    </a>
                    <div class="dropdown-menu  dropdown-menu-right ">
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{__('Welcome!')}}</h6>
                    </div>
                    <a href="{{ url('admin/admin_edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{__('My profile')}}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                <i class="ni ni-user-run"></i>
                                <span>{{ __('Logout') }}</span>
                    </a>
                    </div>

                </li>
            </ul>
        @else
            @php
                $worker = App\Models\Coworkers::where('user_id',auth()->user()->id)->first();
            @endphp
            <ul class="navbar-nav align-items-center  ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img src="{{ url('images/upload/'.$worker->image) }}" alt="">
                        </span>
                        <div class="media-body  ml-2  d-none d-lg-block">
                        <span class="mb-0 text-sm  font-weight-bold">{{$worker->name}}</span>
                        </div>
                    </div>
                    </a>
                    <div class="dropdown-menu  dropdown-menu-right ">
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{__('Welcome!')}}</h6>
                    </div>
                    <a href="{{ url('coworker/worker_profile') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{__('My profile')}}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                <i class="ni ni-user-run"></i>
                                <span>{{ __('Logout') }}</span>
                    </a>
                    </div>

                </li>
            </ul>
        @endif
      </div>
    </div>
</nav>
