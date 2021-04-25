<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $company_name = App\Models\Setting::find(1)->company_name;
    @endphp

    <title>{{"Rezo/".$company_name}}</title>

    @php
        $favicon = App\Models\Setting::find(1)->company_favicon;
        $color = App\Models\Setting::find(1)->color;
    @endphp

    <style>
        :root {
            --site_color: <?php echo "#00adb5"; ?>
        }

        .custom_error
        {
            font-size: 80%;
            width: 100%;
            margin-top: .25rem;
            color: #fb6340;
        }
    </style>

    <link rel="icon" href="{{ url('images/magasil.png') }}" type="image/png">

    {{-- <link rel="stylesheet" href="{{ url('assets/css/bootstrap/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ url('assets/css/bootstrap/bootstrap-grid.css') }}">

    <link rel="stylesheet" href="{{ url('assets/css/bootstrap/bootstrap.min.css') }}"> --}}

    <link type="text/css" href="{{ url('assets/css/argon.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{ url('assets/vendor/select2/dist/css/select2.min.css')}}">

    <link rel="stylesheet" href="{{ url('assets/vendor/animate.css/animate.min.css')}}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">

    <link rel="stylesheet" href="{{ url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{url('assets/css/util.css')}}">

	<link rel="stylesheet" type="text/css" href="{{url('assets/css/main.css')}}">

</head>
<body  >
	<div class="limiter ">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
                    <img src="{{ url('images/app_icon.png') }}" class="rounded-lg" alt="IMG">
                </div>
				<form method="POST" action="{{ url('coworker/coworker_confirm_login') }}">
                    @csrf
                    @if ($errors->any())
                    <div class="alert alert-primary alert-dismissible show fade">
                        <div class="alert-body">
                            @foreach ($errors->all() as $item)
                            {{ $item }}
                            @endforeach
                        </div>
                    </div>
                    @endif
					<span class="login100-form-title">
						{{__('Employee login...')}}
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                        <input class="input100 @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email" required>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
                    </div>

                    @error('email')
                    <span class="custom_error" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100 @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password" required>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
                    </div>

                    @error('password')
                    <span class="custom_error" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

					<div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            {{__('Login')}}
						</button>
                    </div>
                    <div class="text-center">
                        <a class="nav-link mt-2">{{__("Don't have an account..?")}}
                            <a href="{{ url('coworker/coworker_register') }}">{{__('Create a account')}}</a>
                        </a>
                    </div>
                </form>
			</div>
		</div>
	</div>
</body>
</html>
