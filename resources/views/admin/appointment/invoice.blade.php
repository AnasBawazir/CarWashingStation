@extends('layouts.app', ['activePage' => 'appointment'])

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card w-75 invoice_card" id="invoice_card">
            <div class="text-right">
                <a href="{{ url('admin/invoice_print/'.$data->id) }}" target="_blank" class="btn btn-primary">print</a>
            </div>
            <div class="card-header">
                <div class="col-12 text-center">
                    <h1>{{__('Invoice')}}</h1>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="offset-md-1 col-md-3">
                        <b>{{__('Shop Details')}}</b><br>
                        {{ $company_data->company_address }}
                    </div>
                    <div class="offset-md-4 col-md-3">
                        <img src="{{ url('images/upload/'.$company_data->company_logo) }}" width="150" height="100" alt="">
                    </div>
                </div>
                <hr class="my-3">
                <div class="row">
                    <div class="offset-md-1 col-md-3">
                        <b>{{__('Invoice to')}}</b><br><br>
                        <b>{{__('User')}}</b><br>
                        {{ $data->user['email'] }}
                        {{ $data->user['phone'] }}
                    </div>
                    <div class="offset-md-4 col-md-3">
                        <b>{{__('Booking Id : ')}}</b> {{ $data->appointment_id }}<br>
                        <b>{{__('Booking date : ')}}</b> {{ $data->date }}<br>
                        <b>{{__('Booking time : ')}}</b> {{ $data->start_time }}<br>
                        <b>{{__('payment type : ')}}</b> {{ $data->payment_type }}<br>
                        <b>{{__('Booking status : ')}}</b>{{ $data->appointment_status }}<br>
                    </div>
                </div>
                <div class="row pt-5">
                    <div class="offset-md-1 col-md-10 offset-md-1">
                        <table class="table align-items-center text-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{__('#')}}</th>
                                    <th scope="col" class="sort" data-sort="budget">{{__('Service name')}}</th>
                                    <th scope="col" class="sort" data-sort="status">{{__('price')}}</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <tr>
                                    @foreach ($data['service'] as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->service_name }}</td>
                                        <td>{{ $company_data->currency_symbol }}{{ $item->price }}</td>
                                    </tr>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row pt-5">
                    <div class="col-md-11 text-right">
                        <b>{{__('Total : ')}}</b> {{ $company_data->currency_symbol }}{{ $data->amount }}<br>
                        <b>{{__('discount : ')}}</b>
                        @if ($data->discount == null)
                            {{ $company_data->currency_symbol }}00<br>
                        @else
                            {{ $company_data->currency_symbol }}{{ $data->discount }}<br>
                        @endif
                        <b>{{__('Total payment : ')}}</b> {{ $company_data->currency_symbol }}{{ $data->amount + $data->discount}}<br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
