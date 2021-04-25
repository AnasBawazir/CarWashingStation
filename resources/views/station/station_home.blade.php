@extends('layouts.app',['activePage' => 'dashboard'])

@section('content')

<div class="container-fluid">
    <div class="header-body">
        <div class="row align-items-center py-4">

        </div>
        <!-- Card stats -->
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{__('Total appointment')}}</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $appointments }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                    <i class="far fa-calendar-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{__('Total reviews')}}</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $reviews }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{__('Today appointments')}}</h5>
                                <span class="h2 font-weight-bold mb-0">{{ count($today_appointments) }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header">
            <h4>{{__('Todays appointments')}}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>{{__('appointment Id')}}</th>
                            <th>{{__('User')}}</th>
                            <th>{{__('coworker')}}</th>
                            <th>{{__('service')}}</th>
                            <th>{{__('duration')}}</th>
                            <th>{{__('amount')}}</th>
                            <th>{{__('Service At')}}</th>
                            <th>{{__('Appointment status')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($today_appointments as $today_appointment)
                        <tr>
                            <td>{{ $today_appointment->appointment_id }}</td>
                            <td>{{ $today_appointment->user['name'] }}</td>
                            <td>{{ $today_appointment->coworker['name'] }}</td>
                            <td>
                                @foreach ($today_appointment->service as $item)
                                    {{ $item->service_name }}
                                @endforeach
                            </td>
                            <td>{{ $today_appointment->duration }} {{__('min')}}</td>
                            <td>{{ $currency }}{{ $today_appointment->amount }}</td>
                            <td>{{ $today_appointment->service_type }}</td>
                            <td>{{ $appointment->appointment_status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
