@extends('layouts.app', ['activePage' => 'appointment'])

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
        @if (old('old_value') == "add_appointment")
            <script type="text/javascript">
                $(function () {
                    $('#insert_model').modal();
                    $('#insert_model').addClass('show');
                });
            </script>
        @endif
        @if (old('old_value') == "add_user")
            <script type="text/javascript">
                $(function () {
                    $('#insert_model_user').modal();
                    $('#insert_model_user').addClass('show');
                });
            </script>
        @endif
    @endif
    <div class="card p-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h1>{{__('Appointment')}}</h1>
                </div>
                <div class="col-4 text-right">
                    @can('admin_appointment_add')
                    <button type="button" class="btn btn-primary" onclick="add_btn()" data-toggle="modal" data-target="#insert_model">
                        {{__('+ Add Appointment')}}
                    </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="table-responsive pt-3">
            <table class="table" id="datatable-basic">
                <thead class="thead-light">
                    <tr>
                        <th>{{__(' Id')}}</th>
                        <th>{{__('User')}}</th>
                        <th>{{__('date')}}</th>
                        <th>{{__('coworker')}}</th>
                        <th>{{__('service')}}</th>
                        <th>{{__('amount')}}</th>
                        <th>{{__('Service At')}}</th>
                        <th>{{__('Appointment status')}}</th>
                        @if(Gate::check('admin_appointment_delete') || Gate::check('admin_appointment_show') )
                            <th>{{__('Action')}}</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($appoinments as $appoinment)
                    <tr>
                        <td>{{ $appoinment->id }}</td>
                        <td>{{ $appoinment->user['name'] }}</td>
                        <td>{{ $appoinment->date }}</td>
                        <td>{{ $appoinment->coworker['name'] }}</td>
                        <td>
                            @foreach ($appoinment->service as $item)
                                {{ $item->service_name }}
                            @endforeach
                        </td>

                        <td>{{ $appoinment->amount }} {{ "﷼" }}</td>
                        <td>{{ $appoinment->service_type }}</td>
                        <td>
                            <select onchange="appointment_status({{$appoinment->id}})" id="appointment_status{{$appoinment->id}}" {{ $appoinment->appointment_status == 'COMPLETE' ? 'disabled' : '' }} {{ $appoinment->appointment_status == 'CANCEL' ? 'disabled' : '' }} class="form-control">
                                <option value="pending" {{ $appoinment->appointment_status == 'PENDING' ? 'selected' : 'disabled' }}>{{__('Pending')}}</option>
                                <option value="accept" {{ $appoinment->appointment_status == 'ACCEPT' ? 'selected' : '' }}>{{__('Accept')}}</option>
                                <option value="approve" {{ $appoinment->appointment_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                <option value="cancel" {{ $appoinment->appointment_status == 'CANCEL' ? 'selected' : '' }}>{{__('Cancel')}}</option>
                                <option value="complete" {{ $appoinment->appointment_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                            </select>
                        </td>
                        @if(Gate::check('admin_appointment_delete') || Gate::check('admin_appointment_show') || Gate::check('appointment_invoice'))
                        <td class="table-actions">
                            @can('admin_appointment_delete')
                            <a href="#" class="table-action ml-2 table-action-delete" onclick="deleteData('admin/appointment',{{ $appoinment->id }})">
                                <i class="fas fa-trash"></i>
                            </a>
                            @endcan

                            @can('admin_appointment_show')
                                <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#show_model" onclick="appoinment_show({{$appoinment->id}})">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endcan

{{--                            @can('appointment_invoice')--}}
{{--                                <a href="{{ url('admin/appointment_invoice/'.$appoinment->id) }}" class="ml-2"><i class="fas fa-file-invoice"></i></a>--}}
{{--                            @endcan--}}
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal right fade" id="insert_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('admin/appointment') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="old_value" value="add_appointment">
                <div class="modal-header">
                    <h1>{{__('Add Appointment')}}</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label">{{__('User name')}}</label>
                        <select class="select2 @error('user_id') is-invalid @enderror" data-toggle="select" title="{{__('select user')}}" name="user_id"
                                data-placeholder="{{__('Select a user')}}">
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}" {{ (collect(old('user_id'))->contains($user->id)) ? 'selected':'' }}>{{$user->name}}</option>
                                @endforeach
                        </select>
                        @error('user_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('category')}}</label><br>
                            <select class="select2 @error('category_id') is-invalid @enderror"
                                data-toggle="select" title="{{__('select category')}}" id="category_id" name="category_id[]"
                                data-placeholder="{{__('Select a category')}}">
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{ (collect(old('category_id'))->contains($category->id)) ? 'selected':'' }}>{{$category->category_name}}</option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('Coworker')}}</label><br>
                            <select class="select2 @error('coworker_id') is-invalid @enderror"
                                data-toggle="select" title="select coworker" id="appointment_coworker_id" name="coworker_id"
                                data-placeholder="{{__('Select a category')}}">
                                @foreach ($coworkers as $coworker)
                                    <option value="{{$coworker->id}}" {{ (collect(old('coworker_id'))->contains($coworker->id)) ? 'selected':'' }}>{{$coworker->name}}</option>
                                @endforeach
                            </select>

                            @error('coworker_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('service')}}</label><br>
                            <select class="select2 @error('service_id') is-invalid @enderror"
                                data-toggle="select" id="service" title="{{__('select service')}}" name="service_id[]"
                                data-placeholder="Select service"  >
                                @foreach ($services as $service)
{{--                                    <option value="{{$service->id}}" >{{$service->name}}</option>--}}
                                    <option value="{{$service->id}}" {{ (collect(old('service_id'))->contains($service->id)) ? 'selected':'' }}>{{$service->name}}</option>

                                @endforeach

                            </select>

                            @error('service_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('Select date')}}</label><br>
                        <input class="flatpickr flatpickr-input form-control @error('date') is-invalid @enderror" id="appointment_calender" name="date" type="text" placeholder="{{__('Select Date')}}">

                        @error('date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('Timeslot')}}</label><br>
                            <select class="select4 @error('start_time') is-invalid @enderror"
                                data-toggle="select" id="timeslot" title="select time" name="start_time"
                                data-placeholder="{{__('Select time')}}" >
                                @foreach ($time_slots as $time_slot)
                                    <option value="{{$time_slot->id}}" >{{$time_slot->time}}</option>
                                @endforeach
                            </select>

                            @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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

<div class="modal right fade" id="show_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h1>{{__('Appointment detail')}}</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <img src="" id="user_image" width="200" height="200" class="rounded-lg p-2">
                    </div>
                    <table class="table">
                        <tr>
                            <td>{{__('Appointment')}}</td>
                            <td id="show_appointment"></td>
                        </tr>

                        <tr>
                            <td>{{__('User')}}</td>
                            <td id="show_user"></td>
                        </tr>


                        <tr>
                            <td>{{__('Service')}}</td>
                            <td id="show_service"></td>
                        </tr>

                        <tr>
                            <td>{{__('Amount')}}</td>
                            <td id="show_amount"></td>
                        </tr>

                        <tr>
                            <td>{{__('Coworker')}}</td>
                            <td id="show_coworker"></td>
                        </tr>

                        <tr>
                            <td>{{__('Date')}}</td>
                            <td id="show_date"></td>
                        </tr>

                        <tr>
                            <td>{{__('Start time')}}</td>
                            <td id="show_start_time"></td>
                        </tr>

                        <tr>
                            <td>{{__('End time')}}</td>
                            <td id="show_end_time"></td>
                        </tr>

                        <tr>
                            <td>{{__('Service At')}}</td>
                            <td id="show_service_at"></td>
                        </tr>

                        <tr>
                            <td>{{__('Appointment Status')}}</td>
                            <td id="show_appointment_status"></td>
                        </tr>

                    </table>
                </div>
        </div>
    </div>
</div>

{{--<div class="modal right fade" id="insert_model_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">--}}
{{--    <div class="modal-dialog" role="document">--}}
{{--        <div class="modal-content">--}}
{{--            <form action="{{ url('admin/user') }}" method="post" enctype="multipart/form-data">--}}
{{--                @csrf--}}
{{--                <input type="hidden" name="old_value" value="add_user">--}}
{{--                <div class="modal-header">--}}
{{--                    <h1>{{__('Add User')}}</h1>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <div class="form-group">--}}
{{--                        <div class="file-upload">--}}
{{--                            <div class="custom-file">--}}
{{--                                <input type="file" class="custom-file-input" name="image" accept=".png, .jpg, .jpeg, .svg" id="customFileLang" lang="en">--}}
{{--                                <label class="custom-file-label" for="customFileLang">{{__('Select file')}}</label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        @error('image')--}}
{{--                        <span class="custom_error" role="alert">--}}
{{--                            <strong>{{ $message }}</strong>--}}
{{--                        </span>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label class="form-control-label">{{__('user name')}}</label>--}}
{{--                        <input class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" type="text" placeholder="name">--}}

{{--                        @error('name')--}}
{{--                        <span class="invalid-feedback" role="alert">--}}
{{--                            <strong>{{ $message }}</strong>--}}
{{--                        </span>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label class="form-control-label">{{__('phone')}}</label>--}}
{{--                        <input class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" name="phone" type="text" placeholder="{{__('phone number')}}" style="text-transform: none">--}}

{{--                        @error('phone')--}}
{{--                        <span class="invalid-feedback" role="alert">--}}
{{--                            <strong>{{ $message }}</strong>--}}
{{--                        </span>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label class="form-control-label">{{__('password')}}</label>--}}
{{--                        <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" style="text-transform: none">--}}

{{--                        @error('password')--}}
{{--                        <span class="invalid-feedback" role="alert">--}}
{{--                            <strong>{{ $message }}</strong>--}}
{{--                        </span>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label class="form-control-label">{{__('email')}}</label>--}}
{{--                        <input class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" type="email" placeholder="{{__('email')}}"  style="text-transform: none">--}}

{{--                        @error('email')--}}
{{--                        <span class="invalid-feedback" role="alert">--}}
{{--                            <strong>{{ $message }}</strong>--}}
{{--                        </span>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <hr class="my-3">--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>--}}
{{--                        <input type="submit" value="{{__('Save')}}" class="btn btn-primary">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
@endsection



