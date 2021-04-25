@extends('layouts.app', ['activePage' => 'notification'])

@section('content')
    @if (Session::has('msg'))
    <script>
        var msg = "<?php echo Session::get('msg'); ?>"
        Swal({
            type: 'success',
            title: msg,
            showConfirmButton: false,
            timer: 2000,
        });
    </script>
    @endif
    <div class="container-fluid mt-5">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h1>{{__('Notification')}}</h1>
                    </div>
                </div>
            </div>
            <form id="checkForm" action="{{ url('admin/send_notification') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row p-2">
                            <div class="col-md-12">
                                <h5 class="mr-5 mb-0">{{__('Title')}}</h5>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-md-12">
                                <textarea name="title" class="form-control @error('title') is-invalid @enderror"></textarea>
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-12">
                                <h5 class="mr-5 mb-0">{{__('Message')}}</h5>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-md-12">
                                <textarea name="msg" class="form-control  @error('title') is-invalid @enderror"></textarea>
                                @error('msg')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-12">
                                <h5 class="mr-5 mb-0">{{__('User')}}</h5>
                            </div>
                        </div>

                        <div class="row p-2">
                            <div class="col-md-12">
                                <select class="select2" name="user_id[]" id="select2" multiple="multiple">
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-12 text-center">
                                <input type="submit" value="{{__('Send notification')}}" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
