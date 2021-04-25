<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --site_color: <?php echo "#00adb5"; ?>
        }

        .custom_error
        {
            font-size: 80%;
            width: 100%;
            margin-top: .25rem;
            color: #393e46;
        }
    </style>

    <link rel="icon" href="{{ url('images/magasil.png') }}" type="image/png">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/util.css')}}">
    <link rel="stylesheet" href="{{ url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/main.css')}}">


</head>
<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 row justify-content-lg-between">
				<div class="login100-pic js-tilt col-lg-5 col-sm" data-tilt>
                    <img src="{{ url('images/app_icon.png') }}" class="rounded-lg" alt="IMG">
				</div>

				@yield('content')
			</div>
		</div>
	</div>
</body>
</html>
