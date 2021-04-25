@extends('layouts.app', ['activePage' => 'faq'])

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
            @if (old('old_value') == "add_faq")
            <script type="text/javascript">
                $(function () {
                    $('#insert_model').modal();
                    $('#insert_model').addClass('show');
                });
            </script>
            @endif
            @if (old('old_value') == "update_faq")
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
                    <h1>{{__('Faq')}}</h1>
                </div>
                <div class="col-4 text-right">
                    @can('faq_add')
                        <button type="button" class="btn btn-primary" onclick="add_btn()" data-toggle="modal" data-target="#insert_model">
                            {{__('+ Add Faq')}}
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
                        <th>{{__('question')}}</th>
                        <th>{{__('answer')}}</th>
                        @if(Gate::check('faq_edit') || Gate::check('faq_delete'))
                            <th>{{__('action')}}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($faqs as $faq)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{ $faq->question }}</td>
                        <td>{{ $faq->answer }}</td>
                        @if(Gate::check('faq_edit') || Gate::check('faq_delete'))
                            <td class="table-actions">
                                @can('faq_edit')
                                <a href="#" class="table-action ml-2" data-toggle="modal" data-target="#edit_model" onclick="faq_edit({{$faq->id}})">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                                @endcan
                                @can('faq_delete')
                                <a href="#" class="table-action ml-2 table-action-delete" onclick="deleteData('admin/faq',{{ $faq->id }})">
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
            <div class="modal-header">
                <h1>{{__('Add Faq')}}</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/faq') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="old_value" value="add_faq">
                    <div class="form-group">
                        <label class="form-control-label">{{__('question')}}</label>
                        <textarea name="question" class="form-control @error('question') is-invalid @enderror"></textarea>

                        @error('question')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('answer')}}</label>
                        <select name="for" class="form-control">
                            <option value="">{{__('user')}}</option>
                            <option value="">{{__('driver')}}</option>
                        </select>

                        @error('for')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('answer')}}</label>
                        <textarea name="answer" class="form-control @error('answer') is-invalid @enderror"></textarea>

                        @error('answer')
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
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal right fade" id="edit_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1>{{__('Update Faq')}}</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form class="edit_faq" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="old_value" value="update_faq">
                    <div class="form-group">
                        <label class="form-control-label">{{__('question')}}</label>
                        <textarea name="question" id="question" class="form-control @error('question') is-invalid @enderror"></textarea>

                        @error('question')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('answer')}}</label>
                        <textarea name="answer" id="answer" class="form-control @error('answer') is-invalid @enderror"></textarea>

                        @error('answer')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">{{__('answer')}}</label>
                        <select name="for" id="for" class="form-control">
                            <option value="user">{{__('user')}}</option>
                            <option value="driver">{{__('driver')}}</option>
                        </select>

                        @error('for')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <hr class="my-3">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <input type="submit" value="{{__('Update Faq')}}" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
