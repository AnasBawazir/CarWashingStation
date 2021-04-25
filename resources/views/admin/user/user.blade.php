@extends('layouts.app',['activePage' => 'user'])

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
        @if (old('old_value') == "add_user")
            <script type="text/javascript">
                window.onload = () =>
                {
                    document.querySelector('[data-target="#insert_model"]').click();
                }
            </script>
        @endif
        @if (old('old_value') == "update_user")
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
                    <h1>{{__('User')}}</h1>
                </div>
                <div class="col-4 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#insert_model">
                        {{__('+ Add User')}}
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive pt-3">
            <table class="table table-flush" id="datatable-basic">
                <thead class="thead-light">
                    <tr>
                        <th>{{__('#')}}</th>
                        <th>{{__('User image')}}</th>
                        <th>{{__('User name')}}</th>
                        <th>{{__('user email')}}</th>
                        <th>{{__('block / unblock')}}</th>
                        <th>{{__('actions')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>
                            <a href="{{ url('admin/user/'.$user->id) }}"><img src="{{ url('images/upload/'.$user->image) }}" width="50" height="50" class="rounded-circle" alt=""></td>
                            </a>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->status == 1)
                            <a href="{{ url('admin/block/'.$user->id) }}"
                                class="btn btn-sm btn-danger">{{__('Block')}}</a>
                            @else
                            <a href="{{ url('admin/unblock/'.$user->id) }}"
                                class="btn btn-sm btn-danger">{{__('Unblock')}}</a>
                            @endif
                        </td>
                        <td class="table-actions">
                            <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#edit_model" onclick="user_edit({{$user->id}})">
                                <i class="fas fa-user-edit"></i>
                            </a>
                            <a href="#" class="table-action ml-2 table-action-delete" onclick="deleteData('admin/user',{{ $user->id }})">
                                <i class="fas fa-trash"></i>
                            </a>
                            <a href="{{ url('admin/user/'.$user->id) }}" class="table-action ml-2">
                                <i class="fas fa-eye"></i>
                            </a>
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
            <form action="{{ url('admin/user') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="old_value" value="add_user">
                <div class="modal-header">
                    <h1>{{__('Add User')}}</h1>
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
                        <label class="form-control-label">{{__('user name')}}</label>
                        <input class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" type="text" placeholder="{{__('name')}}">

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('phone')}}</label>
                        <input class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" name="phone" type="text" placeholder="{{__('phone number')}}" style="text-transform: none">

                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('password')}}</label>
                        <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" placeholder="{{__('password')}}" style="text-transform: none">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('email')}}</label>
                        <input class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" type="email" placeholder="{{__('email')}}"  style="text-transform: none">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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
            <form class="edit_user" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="old_value" value="update_user">
                <div class="modal-header">
                    <h1>{{__('Update User')}}</h1>
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
                        <label class="form-control-label">{{__('user name')}}</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" name="name" type="text" placeholder="{{__('name')}}">

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('phone')}}</label>
                        <input class="form-control @error('phone') is-invalid @enderror" id="phone" value="{{ old('phone') }}" name="phone" type="text" placeholder="{{__('phone number')}}" style="text-transform: none">

                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('Add new password')}}</label>
                        <input class="form-control @error('password') is-invalid @enderror" name="password" type="{{__('password')}}" placeholder="password" style="text-transform: none">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('email')}}</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" name="email" type="email" placeholder="email"  style="text-transform: none">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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
@endsection
