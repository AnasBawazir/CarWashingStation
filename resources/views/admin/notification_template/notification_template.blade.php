@extends('layouts.app', ['activePage' => 'notification_template'])

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                @php
                $item = App\Models\NotificationTemplate::where('title','user appointment book')->first();
                @endphp
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <ul class="nav nav-pills nav-pills-rose nav-pills-icons flex-column" role="tablist">
                                @foreach ($data as $value)
                                    <li class="nav-item">
                                        <a class="nav-link mt-1 w-100 h-100 {{ $loop->iteration == 1 ? 'active show' : '' }}"
                                            onclick="notificationTemplateEdit({{ $value->id }})" data-toggle="tab"
                                            href="#link110" role="tablist">
                                            {{ $value->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-5">
                                <table class="code-table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input value="{Customer_name}" class="txtCode" readonly="readonly"> : {{__('Name of customer')}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input value="{service_name}" class="txtCode" readonly="readonly"> : {{__('Name of service')}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input value="{company_name}" class="txtCode" readonly="readonly"> : {{__('Name of company')}}</td>
                                        </tr>
                                        <tr>
                                            <td><input value="{company_website}" class="txtCode" readonly="readonly"> : {{__('Website of company')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="link110">
                                    <form method="post"  action="{{ 'notification_template/'.$item->id }}" class="edit_notification_template_form">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col">
                                                <h2 id="heading">{{ $item->title }}</h2>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="usr">{{__('Title')}}</label>
                                                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                                                            value="{{ $item->title }}" id="title" style="width:100%; text-transform: none" readonly>

                                                    @error('title')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="usr">{{__('Subject')}}</label>
                                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" name="subject"
                                                            value="{{ $item->subject }}" id="subject" style="width:100%; text-transform: none">

                                                    @error('subject')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="usr">{{__('Notification content')}}</label>
                                                    <textarea name="notification_content" id="notification_content" name="notification_content" class="form-control" id="" cols="30" rows="10" style="text-transform: none">{{ $item->notification_content }}</textarea>

                                                    @error('notification_content')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="usr">{{__('mail content')}}</label>
                                                    <textarea name="mail_content" class="form-control textarea_editor" cols="10" rows="10" id="mail_content" style="text-transform: none">{{ $item->mail_content }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <div class="text-center">
                                                        <input type="submit" value="{{__('Save')}}" class="btn btn-primary">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
