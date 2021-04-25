@extends('layouts.app', ['activePage' => 'role'])

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

        Toast.fire(
        {
            icon: 'success',
            title: msg
        })
    </script>
    @endif

    @if (Session::has('errors'))
    @if (old('old_value') == "add_role")
    <script type="text/javascript">
        $(function () {
                    $('#insert_model').modal();
                    $('#insert_model').addClass('show');
                });
    </script>
    @endif
    @if (old('old_value') == "update_role")
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
                    <h1>{{__('Role')}}</h1>
                </div>
                <div class="col-4 text-right">
                    @can('role_add')
                        <button type="button" class="btn btn-primary" onclick="add_btn()" data-toggle="modal" data-target="#insert_model">
                            {{__('+ Add Role')}}
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
                        <th>{{__('Role name')}}</th>
                        <th>{{__('permissions')}}</th>
                        @if(Gate::check('role_edit'))
                        <th>{{__('Action')}}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @forelse ($role->permissions as $permission)
                            <span class="badge badge-pill badge-primary">{{ $permission->name }}</span>
                            @empty
                            <span class="badge badge-pill badge-primary"></span>
                            @endforelse
                        </td>
                        @if(Gate::check('role_edit'))
                        <td class="table-actions">
                            @can('role_edit')
                            <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#edit_model"
                                onclick="role_edit({{$role->id}})">
                                <i class="fas fa-user-edit"></i>
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
            <div class="modal-header">
                <h1>{{__('Add Role')}}</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/role') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="old_value" value="add_role">
                    <div class="form-group">
                        <label class="form-control-label">{{__('Name')}}</label>
                        <input type="text" name="name" required class="form-control" style="text-transform: none">

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('Permissions')}}</label>
                        <select name="permissions[]"
                            class="select2 form-control @error('permissions') is-invalid @enderror" required multiple
                            dir="{{ session()->has('direction')&& session('direction') == 'rtl'? 'rtl':''}}">
                            @foreach ($permissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>

                        <span class="invalid-feedback" role="alert">
                        </span>
                    </div>

                    <hr class="my-3">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal right fade" id="edit_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1>{{__('Update Role')}}</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="edit_role" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="old_value" value="update_role">
                    <div class="form-group">
                        <label class="form-control-label">{{__('Name')}}</label>
                        <input type="text" name="name" id="update_name" readonly class="form-control"
                            style="text-transform: none">
                        <span class="invalid-feedback name" role="alert">
                        </span>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('Permissions')}}</label>
                        <select name="permissions[]" id="update_permission"
                            class="select2 form-control @error('permissions') is-invalid @enderror" required multiple
                            dir="{{ session()->has('direction')&& session('direction') == 'rtl'? 'rtl':''}}">
                            @foreach ($permissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>

                        <span class="invalid-feedback permissions" role="alert">
                        </span>
                    </div>

                    <hr class="my-3">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <input type="submit" value="{{__('Update role')}}" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
