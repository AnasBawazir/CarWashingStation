@extends('layouts.app', ['activePage' => 'service'])

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
            @if (old('old_value') == "add_service")
                <script type="text/javascript">
                    $(function () {
                        $('#insert_model').modal();
                        $('#insert_model').addClass('show');
                    });
                </script>
            @endif
            @if (old('old_value') == "update_service")
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
                        <h1>{{__('Service')}}</h1>
                    </div>
                    <div class="col-4 text-right">
                        <button type="button" class="btn btn-primary" onclick="add_btn()" data-toggle="modal" data-target="#insert_model">
                            {{__('+ Add Service')}}
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive pt-3">
                <table class="table table-flush" id="datatable-basic">
                    <thead class="thead-light">
                        <tr>
                            <th>{{__('#')}}</th>
                            <th>{{__('Service image')}}</th>
                            <th>{{__('service name')}}</th>
                            <th>{{__('price')}}</th>
                            <th>{{__('Coworker')}}</th>
                            <th>{{__('enable')}}</th>
                            @if(Gate::check('service_show') || Gate::check('service_edit') || Gate::check('service_delete'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                        <tr>
                            <th>{{$loop->iteration}}</th>
                            <td>
                                <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#show_model"
                                onclick="service_show({{$service->id}})">
                                <img src="{{ url('images/upload/'.$service->image) }}" class="rounded-circle" width="50" height="50" alt=""></a>
                            </td>
                            <td>{{ $service->service_name }}</td>
                            <td>{{ $service->price }}</td>
                            <td>{{ $service->coworker_id }}</td>
                            <td>
                                <label class="custom-toggle">
                                    <input type="checkbox" id="update_status" name="status" {{$service->status == 1 ? 'checked' : ''}} onclick="change_status('admin/service',{{ $service->id }})">
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('No')}}" data-label-on="{{__('Yes')}}"></span>
                                </label>
                            </td>
                            @if(Gate::check('service_show') || Gate::check('service_edit') || Gate::check('service_delete'))
                            <td class="table-actions">
                                @can('service_edit')
                                <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#edit_model" onclick="serivce_edit({{$service->id}})">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                                @endcan

                                @can('service_delete')
                                <a href="#" class="table-action ml-2 table-action-delete" onclick="deleteData('admin/service',{{ $service->id }})">
                                    <i class="fas fa-trash"></i>
                                </a>
                                @endcan

                                @can('service_show')
                                <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#show_model" onclick="service_show({{$service->id}})">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
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
                <form action="{{ url('admin/service') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="old_value" value="add_service">
                    <div class="modal-header">
                        <h1>{{__('Add Service')}}</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

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
                            <label class="form-control-label">{{__('Service name')}}</label>
                            <input class="form-control @error('service_name') is-invalid @enderror" name="service_name" type="text"
                            placeholder="{{__('service name')}}" value="{{ old('service_name') }}" style="text-transform: none">
                            @error('service_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('Price')}}</label><br>
                            <input class="form-control @error('price') is-invalid @enderror" name="price" type="number"
                                placeholder="{{__('price')}}" value="{{ old('price') }}" style="text-transform: none">

                            @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('duration in min')}}</label><br>
                            <input class="form-control @error('duration') is-invalid @enderror" name="duration" type="number"
                                placeholder="30" value="{{ old('duration') }}" style="text-transform: none">

                            @error('duration')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('category')}}</label><br>
                                <select class="form-control select2 @error('category_id') is-invalid @enderror"
                                    data-toggle="select" title="select category" name="category_id[]"
                                    data-placeholder="{{__('Select a category')}}" multiple dir="{{ session()->has('direction')&& session('direction') == 'rtl'? 'rtl':''}}">
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
                            <select class="form-control select2 @error('coworker_id') is-invalid @enderror"
                                data-toggle="select" name="coworker_id"
                                data-placeholder="Select a end time" dir="{{ session()->has('direction')&& session('direction') == 'rtl'? 'rtl':''}}">
                                @foreach ($coworkers as $coworker)
                                    <option value="{{$coworker->id}}" {{ (collect(old('category_id'))->contains($coworker->id)) ? 'selected':'' }}>{{$coworker->name}}</option>
                                @endforeach
                            </select>

                            @error('coworker_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('description')}}</label><br>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                id="" style="text-transform: none">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('Status')}}</label><br>
                            <label class="custom-toggle">
                                <input type="checkbox" name="status">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="{{__('No')}}" data-label-on="{{__('Yes')}}"></span>
                            </label>
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

    <div class="modal right fade" id="edit_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="edit_service" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="old_value" value="update_service">
                    <div class="modal-header">
                        <h1>{{__('Update Service')}}</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <img src="" id="update_image" width="200" height="200" class="rounded-lg p-2"/>
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
                            <label class="form-control-label">{{__('Service name')}}</label>
                            <input class="form-control @error('service_name') is-invalid @enderror" name="service_name" type="text"
                                placeholder="service name" id="service_name" style="text-transform: none">
                            @error('service_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('Price')}}</label><br>
                            <input class="form-control @error('price') is-invalid @enderror" name="price" type="number"
                                placeholder="price" id="price" style="text-transform: none">

                            @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('duration in min')}}</label><br>
                            <input class="form-control @error('duration') is-invalid @enderror" name="duration" type="number"
                                placeholder="30" id="duration" style="text-transform: none">
                            @error('duration')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('category')}}</label><br>
                            <form>
                                <select class="form-control select2 @error('category_id') is-invalid @enderror"
                                    data-toggle="select" title="select category" id="category_id" name="category_id[]"
                                    data-placeholder="Select a category" multiple dir="{{ session()->has('direction')&& session('direction') == 'rtl'? 'rtl':''}}">
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->category_name}}</option>
                                    @endforeach --}}
                                </select>

                                @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </form>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('Coworker')}}</label><br>
                            <select class="form-control select2 @error('coworker_id') is-invalid @enderror"
                                data-toggle="select" id="coworker_id" title="select coworker" name="coworker_id"
                                data-placeholder="Select a end time" dir="{{ session()->has('direction')&& session('direction') == 'rtl'? 'rtl':''}}">
                                @foreach ($coworkers as $coworker)
                                    <option value="{{$coworker->id}}">{{$coworker->name}}</option>
                                @endforeach
                            </select>

                            @error('coworker_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{__('description')}}</label><br>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                id="" style="text-transform: none"></textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <hr class="my-3">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                            <input type="submit" value="{{__('Update service')}}" class="btn btn-primary">
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
                        <h1>{{__('service detail')}}</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="text-center">
                            <img src="" id="show_image" width="200px" height="200px" class="rounded p-2"/>
                        </div>

                        <table class="table">
                            <tr>
                                <td>{{__('Service name')}}</td>
                                <td id="show_service_name"></td>
                            </tr>

                            <tr>
                                <td>{{__('price')}}</td>
                                <td id="show_price"></td>
                            </tr>

                            <tr>
                                <td>{{__('Duration in min')}}</td>
                                <td id="show_duration"></td>
                            </tr>

                            <tr>
                                <td>{{__('category')}}</td>
                                <td id="show_category"></td>
                            </tr>

                            <tr>
                                <td>{{__('coworker')}}</td>
                                <td id="show_coworker"></td>
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
