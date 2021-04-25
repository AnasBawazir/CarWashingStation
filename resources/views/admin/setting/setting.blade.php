@extends('layouts.app', ['activePage' => 'setting'])

@section('setting')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <ul class="nav nav-pills nav-pills-rose nav-pills-icons flex-column" role="tablist">
                        @if (App\Models\Setting::find(1)->license_verify == 1)
                        <li class="nav-item active">
                            <a class="nav-link mt-1 w-100 h-100 show active" data-toggle="tab" href="#link110"
                                role="tablist" aria-expanded="true">
                                {{__('Company core setting')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link111" role="tablist"
                                aria-expanded="false">
                                {{__('payment setting')}}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link113" role="tablist"
                                aria-expanded="false">
                                {{__('User verification')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link112" role="tablist"
                                aria-expanded="false">
                                {{__('Notification setting')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link117" role="tablist"
                                aria-expanded="false">
                                {{__('Coworker Notification setting')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link114" role="tablist"
                                aria-expanded="false">
                                {{__('Privacy policy')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link115" role="tablist"
                                aria-expanded="false">
                                {{__('Admin setting')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link116" role="tablist"
                                aria-expanded="false">
                                {{__('License setting')}}
                            </a>
                        </li>
                    @else
                    <li class="nav-item show active">
                        <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link116" role="tablist"
                            aria-expanded="false">
                            {{__('License setting')}}
                        </a>
                    </li>
                    @endif
                    </ul>
                </div>

                <div class="offset-md-1 col-md-6">
                    <div class="tab-content">
                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show message_alert error_alert" role="alert">
                            @foreach ($errors->all() as $item)
                            {{ $item }} <br>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            @endforeach
                        </div>
                        @endif
                        @if (App\Models\Setting::find(1)->license_verify == 1)
                            <div class="tab-pane show active" id="link110">
                                <form action="{{ url('admin/update_setting') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-control-label">{{__('Company Logo')}}</label>
                                                <div class="text-center">
                                                    <img src="{{url('images/upload/'.$company_setting->company_logo)}}" id="update_image" width="200" height="200" class="rounded-lg p-2"/>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="company_logo" accept=".png, .jpg, .jpeg, .svg" id="customFileLang" lang="en">
                                                        <label class="custom-file-label" for="customFileLang">{{__('Select file')}}</label>
                                                    </div>
                                                </div>

                                                @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-control-label">{{__('Company Favicon')}} </label>
                                                <div class="text-center">
                                                    <img src="{{url('images/upload/'.$company_setting->company_favicon)}}" id="update_image" width="200" height="200" class="rounded-lg p-2"/>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="company_favicon" accept=".png, .jpg, .jpeg, .svg" id="customFileLang" lang="en">
                                                        <label class="custom-file-label" for="customFileLang">{{__('Select file')}}</label>
                                                    </div>
                                                </div>

                                                @error('company_favicon')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('currency')}}</label>
                                        <select class="form-control select2 @error('currency') is-invalid @enderror" data-toggle="select" title="select currency" name="currency" data-placeholder="Select a currency" id="currency"  dir="{{ session()->has('direction')&& session('direction') == 'rtl'? 'rtl':''}}>
                                            @foreach ($currencies as $currency)
                                            <option value="{{$currency->code}}" {{ $company_setting->currency == $currency->code ? 'selected' : '' }}>{{$currency->country}}&nbsp;&nbsp;({{$currency->currency}})&nbsp;&nbsp;({{$currency->code}})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('currency')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Company name')}}</label>
                                        <input class="form-control @error('company_name') is-invalid @enderror"
                                            value="{{ $company_setting->company_name }}" name="company_name" type="text"
                                            placeholder="company name" style="text-transform:none;">
                                        @error('company_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <?php
                                        $lat = App\Models\Setting::find(1)->latitude;
                                        $lang = App\Models\Setting::find(1)->longitude;
                                    ?>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Company address')}}</label>
                                        <div id="map-custom" class="map-canvas" data-lat="{{ $lat }}" data-lng="{{ $lang }}"
                                            style="height: 300px;"></div>
                                        <input type="hidden" name="latitude" id="lat" value="{{ $lat }}">
                                        <input type="hidden" name="longitude" id="lang" value="{{ $lang }}">
                                        <textarea name="company_address"
                                            class="form-control @error('company_address') is-invalid @enderror"
                                            id="company_address" cols="30" rows="10" placeholder="company address"
                                            style="text-transform:none;">{{ $company_setting->company_address }}</textarea>

                                        @error('company_address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Phone number')}}</label>
                                        <input class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ $company_setting->phone }}" name="phone" type="text"
                                            placeholder="phone number" style="text-transform:none;">

                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Website')}}</label>
                                        <input class="form-control @error('website') is-invalid @enderror"
                                            value="{{ $company_setting->website }}" name="website" type="text"
                                            placeholder="website" style="text-transform:none;">
                                        @error('website')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('map key')}}</label>
                                        <input class="form-control @error('map_key') is-invalid @enderror"
                                            value="{{ $company_setting->map_key }}" name="map_key" type="text"
                                            placeholder="map key" style="text-transform:none;">
                                        @error('website')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <hr class="my-3">
                                    <div class="text-center">
                                        <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="link111">
                                <form action="{{ url('admin/update_payment_setting') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-control-label">{{__('Service paid locally')}}</label><br>
                                            <label class="custom-toggle">
{{--                                                <input type="checkbox" name="cod"--}}
{{--                                                    {{ $payment_setting->cod == 1 ? 'checked' : '' }}>--}}
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('no')}}" data-label-on="{{__('yes')}}"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-control-label">{{__('Paypal')}}</label><br>
                                            <label class="custom-toggle">
{{--                                                <input type="checkbox" name="paypal"--}}
{{--                                                    {{ $payment_setting->paypal == 1 ? 'checked' : '' }}>--}}
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('no')}}" data-label-on="{{__('yes')}}"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-control-label">{{__('Stripe')}}</label><br>
                                            <label class="custom-toggle">
{{--                                                <input type="checkbox" name="stripe"--}}
{{--                                                    {{ $payment_setting->stripe == 1 ? 'checked' : '' }}>--}}
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('no')}}" data-label-on="{{__('yes')}}"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-control-label">{{__('Razorpay')}}</label><br>
                                            <label class="custom-toggle">
{{--                                                <input type="checkbox" name="razorpay"--}}
{{--                                                    {{ $payment_setting->razorpay == 1 ? 'checked' : '' }}>--}}
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('no')}}" data-label-on="{{__('yes')}}"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group mt-5">
                                        <label for="usr">{{__('PayPal Environment Sandbox')}}</label>
{{--                                        <input type="text"--}}
{{--                                            class="form-control @error('paypal_sendbox') is-invalid @enderror"--}}
{{--                                            name="paypal_sendbox" id="paypal-username"--}}
{{--                                            style="width:100%; text-transform: none"--}}
{{--                                            value="{{ $payment_setting->paypal_sendbox }}">--}}

                                        @error('paypal_sendbox')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>

                                    <div class="form-group">
                                        <label for="usr">{{__('PayPal Environment Production')}}</label>
{{--                                        <input type="text"--}}
{{--                                            class="form-control @error('paypal_production') is-invalid @enderror"--}}
{{--                                            name="paypal_production" id="paypal-password"--}}
{{--                                            style="width:100%; text-transform: none"--}}
{{--                                            value="{{ $payment_setting->paypal_production }}">--}}

                                        @error('paypal_production')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="usr">{{__('Stripe Published Key')}}</label>
{{--                                        <input type="text"--}}
{{--                                            class="form-control @error('stripe_publish_key') is-invalid @enderror"--}}
{{--                                            name="stripe_publish_key" id="strip-publish"--}}
{{--                                            style="width:100%; text-transform: none"--}}
{{--                                            value="{{ $payment_setting->stripe_publish_key }}">--}}

                                        @error('stripe_publish_key')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="usr">{{__('Stripe Secret Key')}}</label>
{{--                                        <input type="text"--}}
{{--                                            class="form-control @error('stripe_secret_key') is-invalid @enderror"--}}
{{--                                            name="stripe_secret_key" id="strip-secret"--}}
{{--                                            style="width:100%; text-transform: none"--}}
{{--                                            value="{{ $payment_setting->stripe_secret_key }}">--}}

                                        @error('stripe_secret_key')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="usr">{{__('Razorpay key')}}</label>
{{--                                        <input type="text" class="form-control @error('razorpay_key') is-invalid @enderror"--}}
{{--                                            name="razorpay_key" id="razorpay_key" style="width:100%; text-transform: none"--}}
{{--                                            value="{{ $payment_setting->razorpay_key }}">--}}
                                        @error('razorpay_key')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="text-center">
                                            <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="link112">
                                <form action="{{ url('admin/update_notification_setting') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">{{__('Push notification')}}</label><br>
                                                <label class="custom-toggle">
                                                    <input type="checkbox" name="push_notification"
                                                        {{ $company_setting->push_notification == 1 ? 'checked' : '' }}>
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('no')}}" data-label-on="{{__('yes')}}"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">{{__('Mail notification')}}</label><br>
                                                <label class="custom-toggle">
                                                    <input type="checkbox" name="mail_notification"
                                                        {{ $company_setting->mail_notification == 1 ? 'checked' : '' }}>
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('no')}}" data-label-on="{{__('yes')}}"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Onesignal App Id')}}</label><br>
                                        <input type="text"
                                            class="form-control @error('onesignal_app_id') is-invalid @enderror"
                                            name="onesignal_app_id" style="text-transform: none"
                                            placeholder="Onesignal App Id" value="{{ $company_setting->onesignal_app_id }}">

                                        @error('onesignal_app_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Onesignal auth key')}}</label><br>
                                        <input type="text"
                                            class="form-control @error('onesignal_auth_key') is-invalid @enderror"
                                            name="onesignal_auth_key" style="text-transform: none"
                                            placeholder="Onesignal auth key"
                                            value="{{ $company_setting->onesignal_auth_key }}">

                                        @error('onesignal_auth_key')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Onesignal Rest api key')}}</label><br>
                                        <input type="text" class="form-control @error('rest_api_key') is-invalid @enderror"
                                            name="rest_api_key" style="text-transform: none"
                                            placeholder="Onesignal Rest api key"
                                            value="{{ $company_setting->rest_api_key }}">

                                        @error('rest_api_key')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('project number')}}</label><br>
                                        <input type="text"
                                            class="form-control @error('project_number') is-invalid @enderror"
                                            name="project_number" style="text-transform: none" placeholder="project number"
                                            value="{{ $company_setting->project_number }}">

                                        @error('project_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Mail host')}}</label><br>
                                        <input type="text" class="form-control @error('mail_host') is-invalid @enderror"
                                            name="mail_host" style="text-transform: none" placeholder="mail host"
                                            value="{{ $company_setting->mail_host }}">

                                        @error('mail_host')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Mail port')}}</label><br>
                                        <input type="text" class="form-control @error('mail_port') is-invalid @enderror"
                                            name="mail_port" style="text-transform: none" placeholder="mail port"
                                            value="{{ $company_setting->mail_port }}">

                                        @error('mail_port')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Mail user name')}}</label><br>
                                        <input type="text" class="form-control @error('mail_username') is-invalid @enderror"
                                            name="mail_username" style="text-transform: none" placeholder="mail user name"
                                            value="{{ $company_setting->mail_username }}">

                                        @error('mail_username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Mail password')}}</label><br>
                                        <input type="password"
                                            class="form-control @error('mail_password') is-invalid @enderror"
                                            name="mail_password" style="text-transform: none" placeholder="mail password"
                                            value="{{ $company_setting->mail_password }}">

                                        @error('mail_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Mail encryption')}}</label><br>
                                        <input type="text"
                                            class="form-control @error('mail_encryption') is-invalid @enderror"
                                            name="mail_encryption" style="text-transform: none"
                                            placeholder="mail encryption" value="{{ $company_setting->mail_encryption }}">

                                        @error('mail_encryption')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Mail from address')}}</label><br>
                                        <input type="text"
                                            class="form-control @error('mail_from_address') is-invalid @enderror"
                                            name="mail_from_address" style="text-transform: none"
                                            placeholder="mail from address"
                                            value="{{ $company_setting->mail_from_address }}">

                                        @error('mail_from_address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group setting-button">
                                        <div class="text-center">
                                            <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="link113">
                                <form action="{{ url('admin/update_user_verification') }}" method="post">
                                    @csrf

                                    <div class="alert bg-primary text-white" style="display: none" role="alert">
                                       {{__('At least select one mail or sms')}}
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-control-label">{{__('User verification')}}</label><br>
                                            <label class="custom-toggle">
                                                <input type="checkbox" id="user_verification" name="user_verification"
                                                    {{ $company_setting->user_verification == 1 ? 'checked' : '' }}>
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('no')}}" data-label-on="{{__('yes')}}"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-control-label">{{__('User verify by sms')}}</label><br>
                                            <label class="custom-toggle">
                                                <input type="checkbox" name="sms_verification" id="sms_verification"
                                                    {{ $company_setting->sms_verification == 1 ? 'checked' : '' }}>
                                                <span class="custom-toggle-slider rounded-circle"  data-label-off="{{__('no')}}" data-label-on="{{__('yes')}}"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-control-label">{{__('User verify by email')}}</label><br>
                                            <label class="custom-toggle">
                                                <input type="checkbox" name="mail_verification" id="mail_verification"
                                                    {{ $company_setting->mail_verification == 1 ? 'checked' : '' }}>
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('no')}}" data-label-on="{{__('yes')}}"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group mt-5">
                                        <label for="usr">{{__('Twilio account id')}}</label>
                                        <input type="text" class="form-control @error('twilio_acc_id') is-invalid @enderror"
                                            name="twilio_acc_id" style="width:100%; text-transform: none"
                                            value="{{ $company_setting->twilio_acc_id }}">

                                        @error('twilio_acc_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="usr">{{__('twilio auth token')}}</label>
                                        <input type="text"
                                            class="form-control @error('twilio_auth_token') is-invalid @enderror"
                                            name="twilio_auth_token" style="width:100%; text-transform: none"
                                            value="{{ $company_setting->twilio_auth_token }}">

                                        @error('twilio_auth_token')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="usr">{{__('twilio phone number')}}</label>
                                        <input type="text"
                                            class="form-control @error('twilio_phone_no') is-invalid @enderror"
                                            name="twilio_phone_no" style="width:100%; text-transform: none"
                                            value="{{ $company_setting->twilio_phone_no }}">

                                        @error('twilio_phone_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="text-center">
                                            <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="link114">
                                <form action="{{ url('admin/update_privacy_policy') }}" method="post">
                                    @csrf

                                    <div class="form-group mt-5">
                                        <label for="usr">{{__('Privacy policy')}}</label>
                                        <textarea name="privacy_policy" class="form-control textarea_editor" cols="10"
                                            rows="10" id="privacy_policy"
                                            style="text-transform: none">{{ $company_setting->privacy_policy }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <div class="text-center">
                                            <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="link115">
                                <form action="{{ url('admin/change_color') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Service at home')}}</label><br>
                                        <label class="custom-toggle">
                                            <input type="checkbox" name="service_at_home" id="service_at_home"
                                                {{ $company_setting->service_at_home == 1 ? 'checked' : '' }}>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('No')}}"
                                                data-label-on="{{__('Yes')}}"></span>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Color')}}</label><br>
                                        <input type="color" name="color" value="{{ $company_setting->color }}" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>
                                <div class="tab-pane" id="link116">
                                    <form action="{{ url('admin/update_license') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-control-label">{{__('license code')}}</label><br>
                                            <input type="text" class="form-control @error('license_code') is-invalid @enderror"
                                                name="license_code" style="text-transform: none"
                                                placeholder="license code" value="{{ $company_setting->license_code }}" readonly>

                                            @error('license_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label">{{__('client name')}}</label><br>
                                            <input type="text" class="form-control @error('client_name') is-invalid @enderror"
                                                name="client_name" style="text-transform: none"
                                                placeholder="client name" value="{{ $company_setting->client_name }}" readonly>
                                            @error('client_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <div class="text-center">
                                                <input type="submit" value="{{__('Save')}}" class="btn btn-primary" disabled>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="tab-pane show active" id="link116">
                                    <form action="{{ url('admin/update_license') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-control-label">{{__('license code')}}</label><br>
                                            <input type="text" class="form-control @error('license_code') is-invalid @enderror"
                                                name="license_code" style="text-transform: none"
                                                placeholder="license code" value="{{ $company_setting->license_code }}" required>

                                            @error('license_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label">{{__('client name')}}</label><br>
                                            <input type="text" class="form-control @error('client_name') is-invalid @enderror"
                                                name="client_name" style="text-transform: none"
                                                placeholder="client name" value="{{ $company_setting->client_name }}" required>

                                            @error('client_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <div class="text-center">
                                                <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            <div class="tab-pane" id="link117">
                                <form action="{{ url('admin/update_coworker_notification_setting') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Coworker notification')}}</label><br>
                                        <label class="custom-toggle">
                                            <input type="checkbox" name="coworker_notification"
                                                {{ $company_setting->coworker_notification == 1 ? 'checked' : '' }}>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('no')}}" data-label-on="{{__('yes')}}"></span>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Coworker Onesignal App Id')}}</label><br>
                                        <input type="text"
                                            class="form-control @error('coworker_app_id') is-invalid @enderror"
                                            name="coworker_app_id" style="text-transform: none"
                                            placeholder="Coworker App Id" value="{{ $company_setting->coworker_app_id }}">

                                        @error('coworker_app_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('coworker auth key')}}</label><br>
                                        <input type="text"
                                            class="form-control @error('coworker_auth_key') is-invalid @enderror"
                                            name="coworker_auth_key" style="text-transform: none"
                                            placeholder="coworker auth key"
                                            value="{{ $company_setting->coworker_auth_key }}">

                                        @error('coworker_auth_key')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('Coworker Rest api key')}}</label><br>
                                        <input type="text" class="form-control @error('coworker_rest_api_key') is-invalid @enderror"
                                            name="coworker_rest_api_key" style="text-transform: none"
                                            placeholder="Coworker Rest api key"
                                            value="{{ $company_setting->coworker_rest_api_key }}">

                                        @error('coworker_rest_api_key')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">{{__('coworker project number')}}</label><br>
                                        <input type="text"
                                            class="form-control @error('coworker_project_number') is-invalid @enderror"
                                            name="coworker_project_number" style="text-transform: none" placeholder="coworker project number"
                                            value="{{ $company_setting->coworker_project_number }}">

                                        @error('coworker_project_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group setting-button">
                                        <div class="text-center">
                                            <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                                        </div>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
