@extends('layouts.app',['activePage' => 'worker_review'])

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
                    <h1>{{__('Review')}}</h1>
                </div>
            </div>
        </div>

        <div class="table-responsive pt-3">
            <table class="table table-flush" id="datatable-basic">
                <thead class="thead-light">
                    <tr>
                        <th>{{__('#')}}</th>
                        <th>{{__('user id')}}</th>
                        <th>{{__('review')}}</th>
                        <th>{{__('rate')}}</th>
                        <th>{{__('comment')}}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($reviews as $review)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $review->user }}</td>
                            <td>{{ $review->review }}</td>
                            <td>
                                @for ($i = 1; $i < 6; $i++)
                                    @if ($review->rate >= $i)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </td>
                            <td>{{ $review->comment }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
