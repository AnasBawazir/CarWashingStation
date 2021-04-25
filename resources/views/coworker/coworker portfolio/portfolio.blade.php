@extends('layouts.app',['activePage' => 'portfolio'])

@section('content')

<div class="container-fluid mt-5 p-5">
    <div class="row">
        <div class="col-md-12">
            @can('coworker_portfolio_add')
                <div class="card">
                    <div class="card-header">
                        <h1>{{__('Add Portfolio')}}</h1>
                    </div>

                    <div class="card-body">
                        <form method="post" action="{{ url('coworker/portfolio') }}" enctype="multipart/form-data" class="dropzone" id="dropzone">
                            @csrf
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card p-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h1>{{__('Co-worker Portfolio')}}</h1>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($portfolios as $portfolio)
                        <div class="gallery">
                            <a target="_blank" href="{{$portfolio->image}}">
                                <img src="{{ $portfolio->image }}" alt="portfolio" width="600" height="600">
                            </a>
                            <div class="desc">
                                <a onclick="deleteData('coworker/portfolio',{{ $portfolio->id }})" class="btn btn-danger text-white">{{__('delete')}}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
