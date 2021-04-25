@extends('layouts.app' , ['activePage' => 'home'])

@section('content')

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{__('Edit profile')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form class="edit_coworker" action="{{ url('coworker/update_employee') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="text-center">
                            <img src="{{ url('images/upload/'.$worker->image) }}" id="update_image" width="200" height="200" class="rounded-lg p-2"/>
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" accept=".png, .jpg, .jpeg, .svg" id="customFileLang" lang="en">
                                <label class="custom-file-label" for="customFileLang">{{__('Select file')}}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('Co-worker name')}}</label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text"
                                placeholder="{{__('Co-worker name')}}" id="name" value="{{ $worker->name }}" style="text-transform: none">

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('Email')}}</label><br>
                            <input class="form-control @error('email') is-invalid @enderror" name="email" type="email"
                                placeholder="{{__('email')}}" id="email" value="{{ $worker->email }}" style="text-transform: none">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('Add new password')}}</label><br>
                            <input class="form-control @error('password') is-invalid @enderror" name="password"
                                type="password" id="password" style="text-transform: none">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('Phone number')}}</label><br>
                            <input class="form-control @error('phone') is-invalid @enderror" name="phone" type="text"
                                placeholder="{{__('phone number')}}" value="{{ $worker->phone }}" id="phone" style="text-transform: none">
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('start time')}}</label><br>

                                <select class="form-control select2 @error('start_time') is-invalid @enderror" data-toggle="select" id="start_time" title="{{__('Simple select')}}" name="start_time"
                                    data-placeholder="Select a start time">
                                    @foreach ($timeslots as $timeslot)
                                    <option value="{{$timeslot->time}}" {{ $worker->start_time == $timeslot->time ? 'selected' : '' }}>{{$timeslot->time}}</option>
                                    @endforeach
                                </select>

                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('End time')}}</label><br>
                            <select class="form-control select2 @error('end_time') is-invalid @enderror"
                                data-toggle="select" id="end_time" title="{{__('select end time')}}" name="end_time"
                                data-placeholder="{{__('Select a end time')}}">
                                @foreach ($timeslots as $timeslot)
                                <option value="{{$timeslot->time}}" {{ $worker->end_time == $timeslot->time ? 'selected' : '' }}>{{$timeslot->time}}</option>
                                @endforeach
                            </select>

                            @error('end_time')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('experience (In years)')}}</label><br>
                            <textarea name="experience" id="experience" class="form-control @error('experience') is-invalid @enderror" style="text-transform: none">{{ $worker->experience }}</textarea>

                            @error('experience')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('description')}}</label><br>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" style="text-transform: none">{{ $worker->description }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <hr class="my-3">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                            <input type="submit" value="{{__('Update coworker')}}" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
