@extends('layouts.app', ['activePage' => 'admin'])

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ url('admin/update_admin_profile') }}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <h6 class="heading-small text-muted mb-4">{{__('Admin Information')}}</h6>
                <input type="hidden" name="id" value="{{$admin->id}}">
                <div class="text-center">
                    <img src="{{ url('images/upload/'.$admin->image) }}" id="update_image" width="200" height="200" class="rounded-lg p-2"/>
                </div>
                <div class="form-group">
                    <div class="file-upload p-2">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image" accept=".png, .jpg, .jpeg, .svg" id="customFileLang" lang="en">
                            <label class="custom-file-label" for="customFileLang">{{__('Select file')}}</label>
                        </div>
                    </div>
                    @error('image')
                    <span class="custom_error" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="pl-lg-4">
                    <div class="form-group">
                        <label class="form-control-label" for="input-name">{{__('Name')}}</label>
                        <input type="text" name="name" id="input-name" class="form-control"
                            placeholder="{{__('Name')}}" value="{{ $admin->name }}" required="" autofocus="">

                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="input-email">{{__('Email')}}</label>
                        <input type="email" name="email" id="input-email" class="form-control"
                            placeholder="{{__('Email')}}" value="{{ $admin->email }}" readonly required="">
                    </div>

                    <div class="text-center">
                        <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                    </div>
                </div>
            </form>
            <hr class="my-4">
            <form method="post" action="{{ url('admin/update_password') }}">
                @csrf

                <input type="hidden" name="id" value="{{ $admin->id }}">

                <h6 class="heading-small text-muted mb-4">{{__('Password')}}</h6>

                <div class="pl-lg-4">
                    <div class="form-group">
                        <label class="form-control-label" for="input-current-password">{{__('Current Password')}}</label>
                        <input type="password" name="old_password" id="input-current-password"
                            class="form-control @error('old_password') @enderror" placeholder="{{__('Current Password')}}" required="">

                            @error('old_password')
                            <span class="custom_error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="input-password">{{__('New Password')}}</label>
                        <input type="password" name="password" id="input-password" class="form-control  @error('password') @enderror"
                            placeholder="{{__('New Password')}}" value="" required="">
                            @error('password')
                            <span class="custom_error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="input-password-confirmation">{{__('Confirm New Password')}}</label>
                        <input type="password" name="password_confirmation"
                            id="input-password-confirmation" class="form-control"
                            placeholder="{{__('Confirm New Password')}}" value="" required="">

                            @error('password_confirmation')
                            <span class="custom_error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-4">{{__('Change password')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
