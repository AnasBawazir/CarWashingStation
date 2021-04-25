@extends('layouts.app', ['activePage' => 'stations'])

@section('content')
<div class="container-fluid mt-5 p-5">
    @if (Session::has('msg'))
        <script>
            var msg = "<?php echo Session::get('msg'); ?>"
            const Toast = Swal.mixin(
                {
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                didOpen: (toast) =>
                {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

                Toast.fire({
                icon: 'success',
                title: msg
            })
        </script>
        @endif

    @if (Session::has('errors'))
        @if (old('old_value') == "add_station")
            <script type="text/javascript">
                $(function () {
                    $('#insert_model').modal();
                    $('#insert_model').addClass('show');
                });
            </script>
        @endif
        @if (old('old_value') == "update_station")
            <script type="text/javascript">
                window.onload = () =>
                {
                    document.querySelector('[data-target="#edit_model"]').click();
                }
            </script>
        @endif
    @endif
    <div class="card p-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h1>{{__('Stations')}}</h1>
                </div>
                <div class="col-4 text-right">
                    @can('station_add')
                        <button type="button" class="btn btn-primary" onclick="add_btn()" data-toggle="modal"
                            data-target="#insert_model">
                            {{__('+ Add Station')}}
                        </button>
                    @endcan
                </div>
            </div>
        </div>
        <div class="table-responsive pt-3">
            <table class="table table-flush" id="datatable-basic">
                <thead class="thead-light">
                    <tr>
                        <th>{{__('#')}}</th>
                        <th>{{__('stations image')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('email')}}</th>
                        <th>{{__('enable')}}</th>
                        @if(Gate::check('station_edit') || Gate::check('station_delete') || Gate::check('station_show'))
                            <th>{{__('Action')}}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stations as $station)
                    <tr>
                        <th>{{$loop->iteration}}</th>
                        <td><img src="{{ url('images/upload/'.$station->image) }}" width="50" height="50" class="rounded-circle" alt=""></td>
                        <td>{{ $station->name }}</td>
                        <td>{{ $station->email }}</td>

                        <td>
                            <label class="custom-toggle">
                                <input type="checkbox" name="status" {{ $station->status == 1 ? 'checked' : '' }}
                                    onclick="change_status('admin/stations',{{ $station->id }})">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('No')}}"
                                    data-label-on="{{__('Yes')}}"></span>
                            </label>
                        </td>

                        @if(Gate::check('station_edit') || Gate::check('station_delete') || Gate::check('station_show'))
                            <td class="table-actions">
                                @can('station_edit')
                                <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#edit_model"
                                    onclick="coworker_edit({{$station->id}})">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                                @endcan

                                @can('station_delete')
                                <a href="#" class="table-action ml-2 table-action-delete"
                                    onclick="deleteData('admin/coworkers',{{ $station->id }})">
                                    <i class="fas fa-trash"></i>
                                </a>
                                @endcan

                                @can('station_show')
                                <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#show_model"
                                    onclick="coworker_show({{$station->id}})">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="container demo">
    <div class="modal right fade" id="insert_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('admin/stations') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="old_value" value="add_coworker">
                    <div class="modal-header">
                        <h1>{{__('Add stations')}}</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" id="customFileLang" lang="en">
                                <label class="custom-file-label" for="customFileLang">{{__('Select file')}}</label>
                            </div>
                            @error('image')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('Station name')}}</label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text"
                                placeholder="{{__('Station name')}}" value="{{ old('name') }}" style="text-transform: none">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('Email')}}</label><br>
                            <input class="form-control @error('email') is-invalid @enderror" name="email" type="email"
                                placeholder="{{__('email')}}" value="{{ old('email') }}" style="text-transform: none">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('password')}}</label><br>
                            <input class="form-control @error('password') is-invalid @enderror" name="password"
                                type="password" style="text-transform: none">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('Phone number')}}</label><br>
                            <input class="form-control @error('phone') is-invalid @enderror" name="phone" type="text"
                                placeholder="{{__('phone number')}}" value="{{ old('phone') }}" style="text-transform: none">
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('start time')}}</label><br>
                            <form>
                                <select class="form-control select2 @error('start_time') is-invalid @enderror"
                                    data-toggle="select" title="Simple select" dir="{{ session()->has('direction')&& session('direction') == 'rtl'? 'rtl':''}}" name="start_time"
                                    data-placeholder="{{__('Select a start time')}}">
                                    @foreach ($timeslots as $timeslot)
                                        <option value="{{$timeslot->time}}" {{ (collect(old('start_time'))->contains($timeslot->time)) ? 'selected':'' }}>{{$timeslot->time}}</option>
                                    @endforeach
                                </select>

                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </form>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('End time')}}</label><br>
                            <select class="form-control select2 @error('end_time') is-invalid @enderror"
                                data-toggle="select" title="select end time" name="end_time"
                                data-placeholder="{{__('Select a end time')}}"  dir="{{ session()->has('direction')&& session('direction') == 'rtl'? 'rtl':''}}">
                                @foreach ($timeslots as $timeslot)
                                <option value="{{$timeslot->time}}" {{ (collect(old('end_time'))->contains($timeslot->time)) ? 'selected':'' }}>{{$timeslot->time}}</option>
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
                            <textarea name="experience" class="form-control @error('experience') is-invalid @enderror"
                                 style="text-transform: none">{{ old('experience') }}</textarea>

                            @error('experience')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('description')}}</label><br>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                id="" style="text-transform: none">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('Status')}}</label><br>
                            <label class="custom-toggle">
                                <input type="checkbox" name="status">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('No')}}"
                                data-label-on="{{__('Yes')}}"></span>
                            </label>
                        </div>
                        <hr class="my-3">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                            <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="edit_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="edit_coworker" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="old_value" value="update_coworker">
                    <div class="modal-header">
                        <h1>{{__('Update Co-worker')}}</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <img src="" id="update_image" width="200" height="200" class="rounded-lg p-2"/>
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
                                placeholder="{{__('Co-worker name')}}" id="name" style="text-transform: none">

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('Email')}}</label><br>
                            <input class="form-control @error('email') is-invalid @enderror" name="email" type="email"
                                placeholder="{{__('email')}}" id="email" style="text-transform: none">
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
                                placeholder="{{__('phone number')}}" id="phone" style="text-transform: none">
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('start time')}}</label><br>
                            <form>
                                <select class="form-control select2 @error('start_time') is-invalid @enderror"
                                    data-toggle="select" id="start_time" title="{{__('Simple select')}}" name="start_time"
                                    data-placeholder="Select a start time">
                                    @foreach ($timeslots as $timeslot)
                                    <option value="{{$timeslot->time}}">{{$timeslot->time}}</option>
                                    @endforeach
                                </select>

                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </form>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('End time')}}</label><br>
                            <select class="form-control select2 @error('end_time') is-invalid @enderror"
                                data-toggle="select" id="end_time" title="{{__('select end time')}}" name="end_time"
                                data-placeholder="{{__('Select a end time')}}">
                                @foreach ($timeslots as $timeslot)
                                <option value="{{$timeslot->time}}">{{$timeslot->time}}</option>
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
                            <textarea name="experience" id="experience"
                                class="form-control @error('experience') is-invalid @enderror" id=""
                                style="text-transform: none"></textarea>

                            @error('experience')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('description')}}</label><br>
                            <textarea name="description" id="description"
                                class="form-control @error('description') is-invalid @enderror" id=""
                                style="text-transform: none"></textarea>
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
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="show_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('admin/coworkers') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1>{{__('co-worker detail')}}</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="file-upload text-center p-2">
                            <img src="" id="show_image" width="200px" height="200px" class="rounded"/>
                        </div>

                        <table class="table">
                            <tr>
                                <td>{{__('Co-worker name')}}</td>
                                <td id="show_worker_name"></td>
                            </tr>

                            <tr>
                                <td>{{__('Email')}}</td>
                                <td id="show_email"></td>
                            </tr>

                            <tr>
                                <td>{{__('phone number')}}</td>
                                <td id="show_phone_no"></td>
                            </tr>

                            <tr>
                                <td>{{__('start time')}}</td>
                                <td id="show_start_time"></td>
                            </tr>

                            <tr>
                                <td>{{__('end time')}}</td>
                                <td id="show_end_time"></td>
                            </tr>
                            <tr>
                                <td>{{__('experience')}}</td>
                                <td id="show_experience"></td>
                            </tr>
                            <tr>
                                <td>{{__('description')}}</td>
                                <td id="show_description"></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
