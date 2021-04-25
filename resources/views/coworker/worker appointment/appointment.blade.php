@extends('layouts.app',['activePage' => 'appointment'])

@section('content')

<div class="container-fluid mt-5 p-5">
    <div class="card p-4">
        <div class="card-body">
            <ul class="nav nav-pills mb-3 w-100" id="pills-tab" role="tablist">
                <li class="nav-item w-50">
                    <a class="nav-link active w-100" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                        aria-controls="pills-home" aria-selected="true">{{__('Ongoing Trip')}}</a>
                </li>
                <li class="nav-item w-50">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">{{__('Past trip')}}</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
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
                                @foreach ($ongoings as $ongoing)
                                <tr>
                                    <td>{{ $ongoing->appointment_id }}</td>
                                    <td>{{ $ongoing->user['name'] }}</td>
                                    <td>{{ $ongoing->coworker['name'] }}</td>
                                    <td>
                                        @foreach ($ongoing->service as $item)
                                            {{ $item->service_name }}
                                        @endforeach
                                    </td>
                                    <td>{{ $ongoing->duration }} {{__('min')}}</td>
                                    <td>{{ $currency }}{{ $ongoing->amount }}</td>
                                    <td>{{ $ongoing->service_type }}</td>
                                    <td>{{ $ongoing->appointment_status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
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
                                @foreach ($ongoings as $ongoing)
                                <tr>
                                    <td>{{ $ongoing->appointment_id }}</td>
                                    <td>{{ $ongoing->user['name'] }}</td>
                                    <td>{{ $ongoing->coworker['name'] }}</td>
                                    <td>
                                        @foreach ($ongoing->service as $item)
                                            {{ $item->service_name }}
                                        @endforeach
                                    </td>
                                    <td>{{ $ongoing->duration }} {{__('min')}}</td>
                                    <td>{{ $currency }}{{ $ongoing->amount }}</td>
                                    <td>{{ $ongoing->service_type }}</td>
                                    <td>{{ $ongoing->appointment_status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
