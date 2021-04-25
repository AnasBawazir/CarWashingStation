@extends('layouts.auth')

@section('content')
    <form method="POST" action="{{url('/register') }}">
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
        <span class="login100-form-title text-warning">
						 <h2 class="heading-title pt-3 mb--2 ">
                {{__('Register')}}
            </h2>
					</span>
        <div class="wrap-input100">
            @if(session()->has('message'))
                <div class="login100-form-btn " >
                    {{ session()->get('message') }}
                </div>
            @endif
        </div>

        <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
            <input class="input100 @error('name') is-invalid @enderror" type="name" name="name" placeholder="name" required>
            <span class="focus-input100"></span>
            <span class="symbol-input100">
							<i class="fas fa-signature" aria-hidden="true"></i>
						</span>
        </div>

        <div class="wrap-input100 validate-input" data-validate = "Valid phone is required: ex@abc.xyz">
            <input class="input100 @error('phone') is-invalid @enderror" type="phone" name="phone" placeholder="phone" required>
            <span class="focus-input100"></span>
            <span class="symbol-input100">
                            <i class="fas fa-phone-square"></i>
						</span>
        </div>

        <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
            <input class="input100 @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email" required>
            <span class="focus-input100"></span>
            <span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
        </div>

        <div class="wrap-input100 validate-input" data-validate = "Password is required">
            <input class="input100 @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password" required>
            <span class="focus-input100"></span>
            <span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
        </div>


        <div class="wrap-input100 validate-input" data-validate = "Chose type of car wash">
            <label>
                <select class="input100 @error('car_wash') is-invalid @enderror" type="text" name="car_wash" placeholder="Type of car wash" required>
                        <option value="">Chose type of car wash</option>
                        <option value="station">car wash station</option>
                        <option value="coworker">Mobile car wash</option>

                </select>
            </label>
            <span class="focus-input100"></span>

            <span class="symbol-input100">
							<i class="fa fa-car" aria-hidden="true"></i>
						</span>
        </div>

        <div class="container-login100-form-btn">
            <button class="login100-form-btn">
                {{__('Register')}}
            </button>
        </div>
        <div class="text-center">
            <a class="nav-link mt-2">{{__("Already have a account..?")}}
                <a href="{{ url('/') }}">{{__('Login')}}</a>
            </a>
        </div>
    </form>
@endsection
