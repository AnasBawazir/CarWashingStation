@extends('layouts.app', ['activePage' => 'category'])

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
        @if (old('old_value') == "add_category")
            <script type="text/javascript">
                $(function () {
                    $('#insert_model').modal();
                    $('#insert_model').addClass('show');
                });
            </script>
        @endif
        @if (old('old_value') == "update_category")
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
                    <h1>{{__('category')}}</h1>
                </div>
                @can('category_add')
                <div class="col-4 text-right">
                    <button type="button" class="btn btn-primary" onclick="add_btn()" data-toggle="modal" data-target="#insert_model">
                        {{__('+ Add category')}}
                    </button>
                </div>
                @endcan
            </div>
        </div>

        <div class="table-responsive pt-3">
            <table class="table table-flush" id="dtBasicExample">
                <thead class="thead-light">
                    <tr>
                        <th>{{__('#')}}</th>
                        <th>{{__('category Name')}}</th>
                        <th>{{__('category image')}}</th>
                        <th>{{__('enable')}}</th>
                        @if(Gate::check('category_edit') || Gate::check('category_delete'))
                            <th>{{__('action')}}</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <th>{{$loop->iteration}}</th>
                        <td><img src="{{ url('images/upload/'.$category->image) }}" class="rounded-circle" width="50" height="50" alt=""></td>
                        <td>{{ $category->category_name }}</td>
                        <td>
                            <label class="custom-toggle">
                                <input type="checkbox" id="update_status" name="status" {{$category->status == 1 ? 'checked' : ''}} onclick="change_status('admin/category',{{ $category->id }})">
                                <span class="custom-toggle-slider rounded-circle"   data-label-off="{{__('No')}}" data-label-on="{{__('Yes')}}"></span>
                            </label>
                        </td>

                        @if(Gate::check('category_edit') || Gate::check('category_delete'))
                            <td class="table-actions">
                                @can('category_edit')
                                    <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#edit_model" onclick="category_edit({{$category->id}})">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                @endcan
                                @can('category_delete')
                                <a href="#" class="table-action ml-2 table-action-delete" onclick="deleteData('admin/category',{{ $category->id }})">
                                    <i class="fas fa-trash"></i>
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

<div class="modal right fade" id="insert_model" tabindex="-1" role="modal" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('admin/category') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h1>{{__('Add Category')}}</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" name="old_value" value="add_category">
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
                        <label class="form-control-label">{{__('Category name')}}</label>
                        <input class="form-control @error('category_name') is-invalid @enderror" name="category_name" value="{{ old('category_name') }}" type="text" placeholder="{{__('category name')}}">

                        @error('category_name')
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
            <form class="edit_category" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="old_value" value="update_category">
                <div class="modal-header">
                    <h1>{{__('Update Category')}}</h1>
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
                        <label class="form-control-label">{{__('Category name')}}</label>
                        <img src="" alt="" srcset="">
                        <input class="form-control" id="category_name" name="category_name" type="text" placeholder="{{__('category name')}}">
                    </div>
                    <hr class="my-3">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <input type="submit" value="{{__('Update category')}}" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
