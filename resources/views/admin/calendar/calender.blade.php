@extends('layouts.app', ['activePage' => 'calendar'])

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-sm pending">{{__('Pending')}}</button>
            <button class="btn btn-sm reject">{{__('Accept')}}</button>
            <button class="btn btn-sm approve">{{__('Approve')}}</button>
            <button class="btn btn-sm complete">{{__('Complete')}}</button>
            <button class="btn btn-sm cancel">{{__('Cancel')}}</button>

        </div>
        <div class="card-body">
            <div id="calender">
                {!! $calendar->calendar() !!}
                {!! $calendar->script() !!}
            </div>
        </div>
    </div>

    <div class="modal right fade" id="show_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('admin/coworkers') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1>{{__('appointment detail')}}</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="file-upload text-center p-1">
                            <img src="" id="user_image" width="150" height="150" class="rounded-lg"/>
                        </div>

                        <table class="table">
                            <tr>
                                <td>{{__('Appointment Id')}}</td>
                                <td id="appointment_id"></td>
                            </tr>
                            <tr>
                                <td>{{__('Name')}}</td>
                                <td id="user_name"></td>
                            </tr>
                            <tr>
                                <td>{{__('Coworker name')}}</td>
                                <td id="Coworker_name"></td>
                            </tr>
                            <tr>
                                <td>{{__('service name')}}</td>
                                <td id="service_name"></td>
                            </tr>
                            <tr>
                                <td>{{__('Service at')}}</td>
                                <td id="service_at"></td>
                            </tr>
                            <tr>
                                <td>{{__('Date')}}</td>
                                <td id="date"></td>
                            </tr>
                            <tr>
                                <td>{{__('start time')}}</td>
                                <td id="start_time"></td>
                            </tr>
                            <tr>
                                <td>{{__('end time')}}</td>
                                <td id="end_time"></td>
                            </tr>
                            <tr>
                                <td>{{__('Duration')}}</td>
                                <td id="duration"></td>
                            </tr>
                            <tr>
                                <td>{{__('Amount')}}</td>
                                <td id="amount"></td>
                            </tr>
                            <tr>
                                <td>{{__('payment status')}}</td>
                                <td id="payment_status"></td>
                            </tr>
                            <tr>
                                <td>{{__('Appointment status')}}</td>
                                <td id="appointment_status"></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
