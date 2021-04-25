@extends('layouts.auth')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="col-lg-5 col-sm">
        @csrf

        <div class="login100-form-title mt--5 mb--3">
            <img src="{{url('images/avatar.svg')}}" height="100" width="100">
            <h1 class="heading-title pt-3 mb--2">
                {{__('Welcome')}}
            </h1>
        </div>


        <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
            <input class="input100 @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email"
                   required>
            <span class="focus-input100"></span>
            <span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
            @if ($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>


        <div class="wrap-input100 validate-input" data-validate="Password is required">
            <input class="input100 @error('password') is-invalid @enderror" type="password" name="password"
                   placeholder="Password" required>
            <span class="focus-input100"></span>
            <span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
            @if ($errors->has('password'))
                <div class="invalid-feedback">
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>


        <div class="container-login100-form-btn">
            <button class="login100-form-btn">
                {{__('Login')}}
            </button>
        </div>

    </form>

@endsection
