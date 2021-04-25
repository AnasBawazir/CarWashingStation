<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">

    <meta name="author" content="Creative Tim">

    @php
        $company_name = App\Models\Setting::find(1)->company_name;
    @endphp

    <title>{{$company_name}}</title>

    @php
        $favicon = App\Models\Setting::find(1)->company_favicon;
        $color = App\Models\Setting::find(1)->color;
    @endphp

    <script src="{{ url('assets/js/jquery.min.js')}}"></script>

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> --}}

    <style>
        :root {
            /* --blue: #6B48FF; */
            --site_color: <?php echo $color; ?>
        }
    </style>

    <script>
        window.print();
    </script>
    <link rel="icon" href="{{ url('images/upload/'.$favicon) }}" type="image/png">

    <link rel="stylesheet" href="{{ url('assets/css/bootstrap/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ url('assets/css/bootstrap/bootstrap-grid.css') }}">

    <link rel="stylesheet" href="{{ url('assets/css/bootstrap/bootstrap.min.css') }}">

    <input type="hidden" value="{{url('/')}}" id="mainurl">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">

    <link rel="stylesheet" href="{{ url('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">

    <link rel="stylesheet" href="{{ url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">

    <link rel="stylesheet" href="{{ url('assets/css/wysiwyg-color.css')}}" type="text/css">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>

    <link rel="stylesheet" href="{{ url('assets/css/argon.css')}}" type="text/css">

    <link rel="stylesheet" href="{{ url('assets/css/custom.css')}}" type="text/css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
    <body>
        <div class="page">
            <div class="card" id="invoice_card">
                <div class="card-header">
                    <div class="col-12 text-center">
                        <h1>{{__('Invoice')}}</h1>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <b>{{__('Shop Details')}}</b><br>
                            {{ $company_data->company_address }}
                        </div>
                        <div class="col-6 text-right">
                            <img src="{{ url('images/upload/'.$company_data->company_logo) }}" width="150" height="100" alt="">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-6">
                            <b>{{__('Invoice to')}}</b><br><br>
                            <b>{{__('User')}}</b><br>
                            {{ $data->user['email'] }}
                            {{ $data->user['phone'] }}
                        </div>
                        <div class="col-6 text-right">
                            <b>{{__('Booking Id : ')}}</b> {{ $data->appointment_id }}<br>
                            <b>{{__('Booking date : ')}}</b> {{ $data->date }}<br>
                            <b>{{__('Booking time : ')}}</b> {{ $data->start_time }}<br>
                            <b>{{__('payment type : ')}}</b> {{ $data->payment_type }}<br>
                            <b>{{__('Booking status : ')}}</b> {{ $data->appointment_status }}<br>
                        </div>
                    </div>
                    <div class="row pt-5">
                        <div class="col-12">
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
                        <div class="col-12 text-right">
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
    </body>
</html>
