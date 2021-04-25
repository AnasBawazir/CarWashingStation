@extends('layouts.app', ['activePage' => 'offer'])

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
        @if (old('old_value') == "add_offer")
            <script type="text/javascript">
                    $(function () {
                        $('#insert_model').modal();
                        $('#insert_model').addClass('show');
                    });
            </script>
        @endif
        @if (old('old_value') == "update_offer")
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
                    <h1>{{__('Offer')}}</h1>
                </div>
                <div class="col-4 text-right">
                    @can('offer_add')
                        <button type="button" class="btn btn-primary" onclick="add_btn()" data-toggle="modal" data-target="#insert_model">
                            {{__('+ Add Offer')}}
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
                        <th>{{__('Offer image')}}</th>
                        <th>{{__('code')}}</th>
                        <th>{{__('discount')}}</th>
                        @if(Gate::check('offer_edit') || Gate::check('offer_delete'))
                            <th>{{__('action')}}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($offers as $offer)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>
                            <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#show_model" onclick="offer_show({{$offer->id}})">
                            <img src="{{ url('images/upload/'.$offer->image) }}" width="50" height="50" class="rounded-circle" alt=""></a>
                        </td>
                        <td>{{ $offer->code }}</td>
                        <td>{{ $offer->discount }}</td>
                        <td class="table-actions">
                            @if(Gate::check('offer_edit') || Gate::check('offer_delete') || Gate::check('offer_show'))
                                @can('offer_edit')
                                    <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#edit_model" onclick="offer_edit({{$offer->id}})">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                @endcan
                                @can('offer_delete')
                                    <a href="#" class="table-action ml-2 table-action-delete" onclick="deleteData('admin/coworker',{{ $offer->id }})">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                @endcan

                                @can('offer_show')
                                    <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#show_model"
                                    onclick="offer_show({{$offer->id}})">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan
                            @endif
                        </td>
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
            <form action="{{ url('admin/offer') }}" id="offer_insert" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="old_value" value="add_offer">
                <div class="modal-header">
                    <h1>{{__('Add offer')}}</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="file-upload">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" accept=".png, .jpg, .jpeg, .svg" id="customFileLang" lang="en">
                                <label class="custom-file-label" for="customFileLang">{{__('Select file')}}</label>
                            </div>
                        </div>
                        @error('image')
                        <span class="custom_error" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('code')}}</label>
                        <input class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" name="code" type="text" placeholder="{{__('code')}}">

                        @error('code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('start date')}}</label>
                        <input class="flatpickr flatpickr-input form-control @error('start_date') is-invalid @enderror" name="start_date" type="text" placeholder="{{__('Select Date')}}">

                        @error('start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('end date')}}</label>
                        <input class="flatpickr flatpickr-input form-control @error('end_date') is-invalid @enderror" name="end_date" type="text" placeholder="{{__('Select Date')}}">

                        @error('end_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('type')}}</label>

                        <select class="form-control select2 @error('type') is-invalid @enderror"
                        data-toggle="select" title="select type" name="type">
                            <option value="amount">{{__('Amount')}}</option>
                            <option value="discount">{{__('discount')}}</option>
                        </select>

                        @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('discount in %')}}</label>
                        <input class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount') }}" name="discount" type="number" placeholder="{{__('discount')}}">

                        @error('discount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('desciption')}}</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="" cols="30" rows="10">{{ old('description') }}</textarea>

                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('Category')}}</label>
                        <select class="form-control select2 @error('category_id') is-invalid @enderror"
                        data-toggle="select" title="select category" name="category_id[]"
                        data-placeholder="Select a category" id="offer_category" multiple>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}" selected>{{$category->category_name}}</option>
                            @endforeach
                        </select>

                        @error('category_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('Service')}}</label>
                        <select class="form-control select2 @error('service_id') is-invalid @enderror"
                        data-toggle="select" title="select category" name="service_id[]"
                        data-placeholder="Select a Service" id="service_id" multiple>
                        @foreach ($services as $service)
                                <option value="{{$service->id}}" selected>{{$service->service_name}}</option>
                            @endforeach
                        </select>

                        @error('service_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <hr class="my-3">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <input type="submit" onclick="offer_save()" value="{{__('Save')}}" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal right fade" id="edit_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="edit_offer" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="old_value" value="update_offer">
                <div class="modal-header">
                    <h1>{{__('Update Offer')}}</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="" id="update_image" width="200" height="200" class="rounded p-2"/>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image" accept=".png, .jpg, .jpeg, .svg" id="customFileLang" lang="en">
                            <label class="custom-file-label" for="customFileLang">{{__('Select file')}}</label>
                        </div>

                        @error('image')
                            <span class="custom_error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('code')}}</label>
                        <input class="form-control @error('code') is-invalid @enderror" id="code" name="code" type="text" placeholder="{{__('code')}}">

                        @error('code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('start date')}}</label>
                        <input class="flatpickr flatpickr-input form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" type="text" placeholder="{{__('Select Date')}}">

                        @error('start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('end date')}}</label>
                        <input class="flatpickr flatpickr-input form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" type="text" placeholder="{{__('Select Date')}}">

                        @error('end_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('discount in %')}}</label>
                        <input class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" type="number" placeholder="{{__('discount')}}">

                        @error('discount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('description')}}</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" cols="30" rows="10"></textarea>

                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('Category')}}</label>
                        <select class="form-control select2 @error('category_id') is-invalid @enderror"
                        data-toggle="select" title="select category" name="category_id[]"
                        data-placeholder="Select a category" id="update_offer_category" multiple>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->category_name}}</option>
                            @endforeach
                        </select>

                        @error('category_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('type')}}</label>
                        <select class="form-control select2 @error('type') is-invalid @enderror"
                            name="type" id="update_type">
                            <option value="amount">{{__('amount')}}</option>
                            <option value="percentage">{{__('percentage')}}</option>
                        </select>

                        @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('Service')}}</label>
                        <select class="form-control select2 @error('service_id') is-invalid @enderror"
                        data-toggle="select" title="select category" name="service_id[]"
                        data-placeholder="Select a Service" id="update_service_id" multiple>
                        @foreach ($services as $service)
                                <option value="{{$service->id}}" selected>{{$service->service_name}}</option>
                            @endforeach
                        </select>

                        @error('service_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <hr class="my-3">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <input type="submit" value="{{__('update offer')}}" class="btn btn-primary">
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
                    <h1>{{__('offer detail')}}</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="file-upload text-center">
                    <img src="" id="show_image" width="200px" height="200px" class="rounded p-1"/>
                </div>

                <div class="modal-body">
                <table class="table">
                        <tr>
                            <td>{{__('Code')}}</td>
                            <td id="show_code"></td>
                        </tr>

                        <tr>
                            <td>{{__('discount in %')}}</td>
                            <td id="show_discount"></td>
                        </tr>

                        <tr>
                            <td>{{__('start date')}}</td>
                            <td id="show_start_date"></td>
                        </tr>

                        <tr>
                            <td>{{__('end date')}}</td>
                            <td id="show_end_date"></td>
                        </tr>

                        <tr>
                            <td>{{__('category')}}</td>
                            <td id="show_category"></td>
                        </tr>

                        <tr>
                            <td>{{__('service')}}</td>
                            <td id="show_service"></td>
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
@endsection
