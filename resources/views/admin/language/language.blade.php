@extends('layouts.app',['activePage' => 'language'])

@section('content')
<div class="container-fluid mt-5">
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
        @if (old('old_value') == "add_language")
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
                    <h1>{{__('Language')}}</h1>
                </div>
                <div class="col-4 text-right">
                    @can('language_add')
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#insert_model">
                        {{__('+ Add Language')}}
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
                        <th>{{__('Image')}}</th>
                        <th>{{__('Language name')}}</th>
                        <th>{{__('Direction')}}</th>
                        <th>{{__('enable')}}</th>
                        @if(Gate::check('language_edit') || Gate::check('language_delete'))
                            <th>{{__('actions')}}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($languages as $language)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>
                            <img src="{{ url('images/upload/'.$language->image) }}" width="50" height="50" class="rounded-circle" alt="">
                        </td>
                        <td>{{ $language->name }}</td>
                        <td>{{ $language->direction }}</td>
                        <td>
                            <label class="custom-toggle">
                                <input type="checkbox" id="update_status" name="status" {{$language->status == 1 ? 'checked' : ''}} onclick="change_status('admin/language',{{ $language->id }})">
                                <span class="custom-toggle-slider rounded-circle"   data-label-off="No" data-label-on="Yes"></span>
                            </label>
                        </td>
                        @if(Gate::check('language_edit') || Gate::check('language_delete'))
                            <td class="table-actions">
                                @can('language_edit')
                                    <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#edit_model" onclick="language_edit({{$language->id}})">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                @endcan
                                @can('language_delete')
                                    <a href="#" class="table-action ml-2 table-action-delete" onclick="deleteData('admin/language',{{ $language->id }})">
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

<div class="modal right fade" id="insert_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('admin/language') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="old_value" value="add_language">
                <div class="modal-header">
                    <h1>{{__('Add Language')}}</h1>
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
                        <label class="form-control-label">{{__('name')}}</label>
                        <input class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" type="text" placeholder="{{__('name')}}">

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('direction')}}</label>
                        <select name="direction" class="select2" id="">
                            <option value="ltr">{{__('Ltr')}}</option>
                            <option value="rtl">{{__('Rtl')}}</option>
                        </select>

                        @error('direction')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('file')}}</label>
                        <input class="form-control @error('file') is-invalid @enderror" value="{{ old('file') }}" name="file" accept=".json" type="file" placeholder="{{__('file')}}"  style="text-transform: none">

                        @error('file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('Status')}}</label><br>
                        <label class="custom-toggle">
                            <input type="checkbox" name="status">
                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
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
            <form class="edit_language" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="old_value" value="update_language">
                <div class="modal-header">
                    <h1>{{__('Update Language')}}</h1>
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
                        <label class="form-control-label">{{__('name')}}</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" name="name" type="text" placeholder="{{__('name')}}" readonly>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('direction')}}</label>
                        <select name="direction" class="select2" id="direction">
                            <option value="ltr">{{__('Ltr')}}</option>
                            <option value="rtl">{{__('Rtl')}}</option>
                        </select>

                        @error('direction')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('file')}}</label>
                        <input class="form-control @error('file') is-invalid @enderror" value="{{ old('file') }}" name="file" id="file" accept=".json" type="file" placeholder="{{__('file')}}"  style="text-transform: none">

                        @error('file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <hr class="my-3">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <input type="submit" value="{{__('Update language')}}" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
