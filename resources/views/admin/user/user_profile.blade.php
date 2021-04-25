@extends('layouts.app',['activePage' => 'user'])

@section('content')
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12 mt-5">
            <div class="card card-profile">
                <div class="row justify-content-center">
                    <div class="">
                        <div class="card-profile-image">
                            <img src="{{ url('images/upload/'.$user->image) }}" width="200" height="150" class="rounded-circle">
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="card-profile-stats d-flex justify-content-center pt-5">
                                <div>
                                    <span class="heading">{{__('Total')}}</span>
                                    <span class="description">{{count($appointments)}}</span>
                                </div>
                                <div>
                                    <span class="heading">{{__('pending')}}</span>
                                    <span class="description">{{count($appointments_pending)}}</span>
                                </div>
                                <div>
                                    <span class="heading">{{__('Cancel')}}</span>
                                    <span class="description">{{count($appointments_cancel)}}</span>
                                </div>
                                <div>
                                    <span class="heading">{{__('Approve')}}</span>
                                    <span class="description">{{count($appointments_approve)}}</span>
                                </div>
                                <div>
                                    <span class="heading">{{__('Complete')}}</span>
                                    <span class="description">{{count($appointments_complete)}}</span>
                                </div>
                                <div>
                                    <span class="heading">{{__('Reject')}}</span>
                                    <span class="description">{{count($appointments_reject)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h5 class="h3">
                            {{$user->name}}
                        </h5>
                        <div class="h5 font-weight-300">
                            {{$user->phone}}
                        </div>
                        <div class="h5 font-weight-300">
                            {{$user->email}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{__('total')}}  <span class="appoint_count">{{count($appointments)}}</span> {{__('appointment booked')}}
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">All</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{__('Pending')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false">{{__('Complete')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4" aria-selected="false">{{__('Approve')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="false">{{__('Reject')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-6-tab" data-toggle="tab" href="#tabs-icons-text-6" role="tab" aria-controls="tabs-icons-text-6" aria-selected="false">{{__('Cancel')}}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card shadow">
                        <div class="card-body user_profile_card">
                            <div class="tab-content" id="myTabContent">
                                {{-- all --}}
                                <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                    <div class="card-body table-responsive">
                                        <table class="table table-flush datatable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{__('#')}}</th>
                                                    <th>{{__('appointment')}}</th>
                                                    <th>{{__('User')}}</th>
                                                    <th>{{__('coworker')}}</th>
                                                    <th>{{__('service')}}</th>
                                                    <th>{{__('duration in min')}}</th>
                                                    <th>{{__('amount')}}</th>
                                                    <th>{{__('appointment status')}}</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($appointments as $appoinment)
                                                <tr>
                                                    <th>{{$loop->iteration}}</th>
                                                    <td>{{ $appoinment->appointment_id }}</td>
                                                    <td>{{ $appoinment->user['name'] }}</td>
                                                    <td>{{ $appoinment->coworker['name'] }}</td>
                                                    <td>
                                                        @foreach ($appoinment->service as $item)
                                                            {{ $item->service_name }}
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $appoinment->duration }}</td>
                                                    <td>{{ $currency }}{{ $appoinment->amount }}</td>
                                                    <td>
                                                        @if ($appoinment->appointment_status == 'PENDING')
                                                            <span class="badge badge-pill pending">{{__('pending')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'COMPLETE')
                                                            <span class="badge badge-pill complete">{{__('complete')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'APPROVE')
                                                            <span class="badge badge-pill approve">{{__('approve')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'REJECT')
                                                            <span class="badge badge-pill reject">{{__('reject')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'CANCEL')
                                                            <span class="badge badge-pill cancel">{{__('cancel')}}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- pending --}}
                                <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="card-body table-responsive">
                                        <table class="table table-flush datatable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{__('#')}}</th>
                                                    <th>{{__('appointment')}}</th>
                                                    <th>{{__('User')}}</th>
                                                    <th>{{__('coworker')}}</th>
                                                    <th>{{__('service')}}</th>
                                                    <th>{{__('duration in min')}}</th>
                                                    <th>{{__('amount')}}</th>
                                                    <th>{{__('appointment status')}}</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($appointments_pending as $appoinment)
                                                <tr>
                                                    <th>{{$loop->iteration}}</th>
                                                    <td>{{ $appoinment->appointment_id }}</td>
                                                    <td>{{ $appoinment->user['name'] }}</td>
                                                    <td>{{ $appoinment->coworker['name'] }}</td>
                                                    <td>
                                                        @foreach ($appoinment->service as $item)
                                                            {{ $item->service_name }}
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $appoinment->duration }}</td>
                                                    <td>{{ $currency }}{{ $appoinment->amount }}</td>
                                                    <td>
                                                        @if ($appoinment->appointment_status == 'PENDING')
                                                            <span class="badge badge-pill pending">{{__('pending')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'COMPLETE')
                                                            <span class="badge badge-pill complete">{{__('complete')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'APPROVE')
                                                            <span class="badge badge-pill approve">{{__('approve')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'REJECT')
                                                            <span class="badge badge-pill reject">{{__('reject')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'CANCEL')
                                                            <span class="badge badge-pill cancel">{{__('cancel')}}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- complete --}}
                                <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                    <div class="card-body table-responsive">
                                        <table class="table table-flush datatable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{__('#')}}</th>
                                                    <th>{{__('appointment')}}</th>
                                                    <th>{{__('User')}}</th>
                                                    <th>{{__('coworker')}}</th>
                                                    <th>{{__('service')}}</th>
                                                    <th>{{__('duration in min')}}</th>
                                                    <th>{{__('amount')}}</th>
                                                    <th>{{__('appointment status')}}</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($appointments_complete as $appoinment)
                                                <tr>
                                                    <th>{{$loop->iteration}}</th>
                                                    <td>{{ $appoinment->appointment_id }}</td>
                                                    <td>{{ $appoinment->user['name'] }}</td>
                                                    <td>{{ $appoinment->coworker['name'] }}</td>
                                                    <td>
                                                        @foreach ($appoinment->service as $item)
                                                            {{ $item->service_name }}
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $appoinment->duration }}</td>
                                                    <td>{{ $currency }}{{ $appoinment->amount }}</td>
                                                    <td>
                                                        @if ($appoinment->appointment_status == 'PENDING')
                                                            <span class="badge badge-pill pending">{{__('pending')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'COMPLETE')
                                                            <span class="badge badge-pill complete">{{__('complete')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'APPROVE')
                                                            <span class="badge badge-pill approve">{{__('approve')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'REJECT')
                                                            <span class="badge badge-pill reject">{{__('reject')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'CANCEL')
                                                            <span class="badge badge-pill cancel">{{__('cancel')}}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- approve --}}
                                <div class="tab-pane fade" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">
                                    <div class="card-body table-responsive">
                                        <table class="table table-flush datatable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{__('#')}}</th>
                                                    <th>{{__('appointment')}}</th>
                                                    <th>{{__('User')}}</th>
                                                    <th>{{__('coworker')}}</th>
                                                    <th>{{__('service')}}</th>
                                                    <th>{{__('duration in min')}}</th>
                                                    <th>{{__('amount')}}</th>
                                                    <th>{{__('appointment status')}}</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($appointments_approve as $appoinment)
                                                <tr>
                                                    <th>{{$loop->iteration}}</th>
                                                    <td>{{ $appoinment->appointment_id }}</td>
                                                    <td>{{ $appoinment->user['name'] }}</td>
                                                    <td>{{ $appoinment->coworker['name'] }}</td>
                                                    <td>
                                                        @foreach ($appoinment->service as $item)
                                                            {{ $item->service_name }}
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $appoinment->duration }}</td>
                                                    <td>{{ $currency }}{{ $appoinment->amount }}</td>
                                                    <td>
                                                        @if ($appoinment->appointment_status == 'PENDING')
                                                            <span class="badge badge-pill pending">{{__('pending')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'COMPLETE')
                                                            <span class="badge badge-pill complete">{{__('complete')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'APPROVE')
                                                            <span class="badge badge-pill approve">{{__('approve')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'REJECT')
                                                            <span class="badge badge-pill reject">{{__('reject')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'CANCEL')
                                                            <span class="badge badge-pill cancel">{{__('cancel')}}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Reject --}}
                                <div class="tab-pane fade" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">
                                    <div class="card-body table-responsive">
                                        <table class="table table-flush datatable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{__('#')}}</th>
                                                    <th>{{__('appointment')}}</th>
                                                    <th>{{__('User')}}</th>
                                                    <th>{{__('coworker')}}</th>
                                                    <th>{{__('service')}}</th>
                                                    <th>{{__('duration in min')}}</th>
                                                    <th>{{__('amount')}}</th>
                                                    <th>{{__('appointment status')}}</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($appointments_reject as $appoinment)
                                                <tr>
                                                    <th>{{$loop->iteration}}</th>
                                                    <td>{{ $appoinment->appointment_id }}</td>
                                                    <td>{{ $appoinment->user['name'] }}</td>
                                                    <td>{{ $appoinment->coworker['name'] }}</td>
                                                    <td>
                                                        @foreach ($appoinment->service as $item)
                                                            {{ $item->service_name }}
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $appoinment->duration }}</td>
                                                    <td>{{ $currency }}{{ $appoinment->amount }}</td>
                                                    <td>
                                                        @if ($appoinment->appointment_status == 'PENDING')
                                                            <span class="badge badge-pill pending">{{__('pending')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'COMPLETE')
                                                            <span class="badge badge-pill complete">{{__('complete')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'APPROVE')
                                                            <span class="badge badge-pill approve">{{__('approve')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'REJECT')
                                                            <span class="badge badge-pill reject">{{__('reject')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'CANCEL')
                                                            <span class="badge badge-pill cancel">{{__('cancel')}}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- cancel --}}
                                <div class="tab-pane fade" id="tabs-icons-text-6" role="tabpanel" aria-labelledby="tabs-icons-text-6-tab">
                                    <div class="card-body table-responsive">
                                        <table class="table table-flush datatable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{__('#')}}</th>
                                                    <th>{{__('appointment')}}</th>
                                                    <th>{{__('User')}}</th>
                                                    <th>{{__('coworker')}}</th>
                                                    <th>{{__('service')}}</th>
                                                    <th>{{__('duration in min')}}</th>
                                                    <th>{{__('amount')}}</th>
                                                    <th>{{__('appointment status')}}</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($appointments_cancel as $appoinment)
                                                <tr>
                                                    <th>{{$loop->iteration}}</th>
                                                    <td>{{ $appoinment->appointment_id }}</td>
                                                    <td>{{ $appoinment->user['name'] }}</td>
                                                    <td>{{ $appoinment->coworker['name'] }}</td>
                                                    <td>
                                                        @foreach ($appoinment->service as $item)
                                                            {{ $item->service_name }}
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $appoinment->duration }}</td>
                                                    <td>{{ $currency }}{{ $appoinment->amount }}</td>
                                                    <td>
                                                        @if ($appoinment->appointment_status == 'PENDING')
                                                            <span class="badge badge-pill pending">{{__('pending')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'COMPLETE')
                                                            <span class="badge badge-pill complete">{{__('complete')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'APPROVE')
                                                            <span class="badge badge-pill approve">{{__('approve')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'REJECT')
                                                            <span class="badge badge-pill reject">{{__('reject')}}</span>
                                                        @endif
                                                        @if ($appoinment->appointment_status == 'CANCEL')
                                                            <span class="badge badge-pill cancel">{{__('cancel')}}</span>
                                                        @endif
                                                    </td>
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
            </div>
        </div>

</div>
@endsection

