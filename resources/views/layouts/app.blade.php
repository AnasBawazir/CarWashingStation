<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">

    <meta name="author" content="Creative Tim">

    <input type="hidden" value="{{url('/')}}" id="mainurl">

    @php
        $favicon = App\Models\Setting::find(1)->company_favicon;
        $color = App\Models\Setting::find(1)->color;
    @endphp

    @php
        $company_name = App\Models\Setting::find(1)->company_name;
    @endphp

    <title>{{'magasil/'.$company_name}}</title>

    <script src="{{ url('assets/js/jquery.min.js')}}"></script>

    <style>
        :root {
            /* --blue: #6B48FF; */
            --site_color: <?php echo "#00adb5"; ?>;
            --hover_color: <?php echo "#393e46".'a1'; ?>;
        }
    </style>
        <link rel="icon" href="{{ url('images/magasil.png') }}" type="image/png">
{{--        <link rel="icon" href="{{ url('images/upload/'.$favicon) }}" type="image/png">--}}

        <meta charset="UTF-8">

        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $company_name = App\Models\Setting::find(1)->company_name;
        @endphp

        <title>{{$company_name}}</title>

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

        <link href="{{ url('assets/vendor/nucleo/css/nucleo.css')}}" rel="stylesheet">

        <link href="{{ url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet">

        {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" /> --}}
        <link rel="stylesheet" href="{{ url('assets/css/select2.min.css') }}">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        <link rel="stylesheet" href="{{ url('assets/css/bootstrap-wysihtml5.css')}}" type="text/css">

        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>

        <link rel="stylesheet" href="{{ url('assets/vendor//sweetalert2/dist/sweetalert2.min.css') }}">

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-flash-1.6.2/b-html5-1.6.2/b-print-1.6.2/datatables.min.css" />

        <link rel="stylesheet" href="{{ url('assets/css/dropzone.css') }}">

        <script src="{{ URL::asset('assets\vendor\sweetalert2\dist\sweetalert2.all.min.js') }}"></script>

        <link type="text/css" href="{{ url('assets/css/argon.css')}}" rel="stylesheet">

        <link rel="stylesheet" href="{{ url('assets/css/custom.css')}}" type="text/css">

        {{-- <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css"> --}}

        {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> --}}
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css"> --}}
            @if (session('direction') == 'rtl')
                <link rel="stylesheet" href="{{ url('assets/css/rtl_direction.css')}}" type="text/css">
            @endif
    </head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
<body class="{{ $class ?? '' }}">

    <div class="Preloader">
        <img src="{{ url('loader/loader.gif') }}" alt="Preloader">
    </div>

    @auth()
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        @include('layouts.navbars.sidebar')
    @endauth

    <div class="main-content">
        @include('layouts.navbars.navbar')
        <div class="header pt-5">
        </div>

        @yield('content')
        @yield('setting')

{{--        <script>--}}
{{--            var a = $('#mainurl').val()+'/admin/setting';--}}
{{--            if (window.location.origin + window.location.pathname != $('#mainurl').val() + '/admin/setting')--}}
{{--            {--}}
{{--                setTimeout(() =>--}}
{{--                {--}}
{{--                    Swal.fire({--}}
{{--                    title: 'Your License is deactivated!',--}}
{{--                    type: 'info',--}}
{{--                    html: 'to get benifit of shinewash please activate your license<br><br> '+--}}
{{--                    '<a href="'+a+'" style="background:#3085d6;color:#fff;padding:8px 10px;border-radius:5px;">Activate License</a>',--}}
{{--                    showCloseButton: false,--}}
{{--                    showCancelButton: false,--}}
{{--                    showConfirmButton: false,--}}
{{--                    focusConfirm: false,--}}
{{--                    onClose: () => {--}}
{{--                    window.location.replace(a);--}}
{{--                }--}}
{{--            })}, 500);--}}
{{--            }--}}
{{--        </script>--}}
{{--        @yield('setting')--}}

    </div>

    <script src="{{ url('assets/js/jquery.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

    <script src="{{ url('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>

    <script src="{{ url('assets/vendor/fullcalendar/dist/fullcalendar.min.js')}}"></script>

    @if(Request::is('admin/setting'))

    <?php
        $google_map_key = App\Models\Setting::first()->map_key;
    ?>
    <script src="{{ url('assets/js/map.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{$google_map_key}}"></script>
    @endif

    <script src="{{ url('assets/js/dropzone.js') }}"></script>

    <script src="{{ url('assets/vendor/chart.js/dist/Chart.min.js') }}"></script>

    <script src="{{ url('assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>

    <script src="{{ url('assets/js/wysihtml5-0.3.0.js') }}"></script>

    <script src="{{ url('assets/sweet_alert/sweetalert.all.js') }}"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-flash-1.6.2/b-html5-1.6.2/b-print-1.6.2/datatables.min.js"></script>

    {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> --}}

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/dropzone.js"></script> --}}

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script> --}}

    <script src="{{ url('assets/js/bootstrap-wysihtml5.js') }}"></script>

    <script src="{{ url('assets/js/argon.js') }}"></script>

    <script src="{{ url('assets/js/custom.js') }}"></script>

</body>

</html>
