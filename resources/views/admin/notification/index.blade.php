@extends('layouts.master')
@section('title','Notification')
@section('action','Lists')

@section('button')

    @can('create_notification')
        <a href="{{ route('admin.notifications.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>Create Notification
            </button>
        </a>
    @endcan
@endsection


@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.notification.common.breadcrumb')

        <div class="search-box p-4 bg-white rounded mb-3 box-shadow pb-2">
            <form class="forms-sample" action="{{route('admin.notifications.index')}}" method="get">
                <h5>Notification Lists</h5>
                <div class="row align-items-center mt-3">
                    <div class="col-lg-4 col-md-8 mb-3">
                        <select class="form-select form-select-lg" name="type" id="type">
                            <option value="" {{!isset($filterParameters['type']) ? 'selected': ''}}   >All Types</option>
                            @foreach(\App\Models\Notification::TYPES as  $value)
                                <option value="{{$value}}" {{ (isset($filterParameters['type']) && $value == $filterParameters['type'] ) ?'selected':'' }} >
                                    {{ucfirst($value)}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 mb-3 ">
                        <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Published Date</th>
                            <th>Type</th>

                            @can('show_notification')
                                <th>Description</th>
                            @endcan

                            <th>Status</th>

                            @canany(['send_notification','delete_notification','edit_notification'])
                                <th>Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @forelse($notifications as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{removeSpecialChars($value->title)}}</td>
                                <td>{{  convertDateTimeFormat($value->notification_publish_date) ?? 'Not published yet'}}</td>
                                <td>{{  ucfirst($value->type)}}</td>

                                @can('show_notification')
                                    <td>
                                        <a href=""
                                           id="showNotificationDescription"
                                           data-href="{{route('admin.notifications.show',$value->id)}}"
                                           data-id="{{ $value->id }}" title="show page content">
                                            <i class="link-icon" data-feather="eye"></i>
                                        </a>
                                    </td>
                                @endcan

                                <td>
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.notifications.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                @canany(['send_notification','delete_notification','edit_notification'])
                                    <td>
                                    <ul class="d-flex list-unstyled mb-0">
                                        @can('edit_notification')
                                            @if($value->type == 'general')
                                                <li class="me-2">
                                                    <a href="{{route('admin.notifications.edit',$value->id)}}" title="Edit notification ">
                                                        <i class="link-icon" data-feather="edit"></i>
                                                    </a>
                                                </li>
                                            @endif
                                        @endcan

                                        @can('delete_notification')
                                            <li class="me-2">
                                                <a class="deleteNotification"
                                                   data-href="{{route('admin.notifications.delete',$value->id)}}" title="Delete notification">
                                                    <i class="link-icon"  data-feather="delete"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('send_notification')
                                            @if($value->type == 'general')
                                                <li >
                                                    <a class="sendNotification"
                                                       data-href="{{route('admin.notifications.send-notification',$value->id)}}" title="send notification">
                                                        <button class="btn btn-primary btn-xs">send notification</button>
                                                    </a>
                                                </li>
                                            @endif
                                        @endcan

                                    </ul>
                                </td>
                                @endcanany


                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">
                                    <p class="text-center"><b>No records found!</b></p>
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="dataTables_paginate mt-3">
            {{$notifications->appends($_GET)->links()}}
        </div>
    </section>

    @include('admin.notification.show')
@endsection

@section('scripts')

    @include('admin.notification.common.scripts')
@endsection






