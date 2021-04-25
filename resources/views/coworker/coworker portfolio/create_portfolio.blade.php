@extends('layouts.app',['activePage' => 'coworker_portfolio'])

@section('content')

<div class="container mt-5 pt-5">
    <div class="card p-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h1>{{__('Co-worker Portfolio')}}</h1>
                </div>
                <div class="col-4 text-right">
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="post" class="dropzone" id="dropzonewidget" action="{{url('coworker/portfolio')}}" enctype="multipart/form-data" class="dropzone" id="dropzone">
                @csrf
            </form>
        </div>
    </div>

    <script type="text/javascript">
        window.onload = function()
        {
            Dropzone.options.dropzone =
            {
                maxFilesize: 10,
                success: function(file,response)
                {
                    console.log("hi helo");
                },
                // error: function(file, response)
                // {
                // return false;
                // }
            };
        };
    </script>
@endsection
