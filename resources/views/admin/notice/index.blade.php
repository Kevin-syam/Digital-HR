
@extends('layouts.master')

@section('title','Notices')

@section('action','Lists')

@section('button')
    @can('create_notice')
        <a href="{{ route('admin.notices.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>Create Notice
            </button>
        </a>
    @endcan
@endsection


@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.notice.common.breadcrumb')

        <div class="search-box p-4 pb-lg-3 bg-white rounded mb-3 box-shadow">
            <form class="forms-sample" action="{{route('admin.notices.index')}}" method="get">
                <h5>Notice Lists</h5>
                <div class="row align-items-center mt-3">
                    <div class="col-lg col-md-6 mb-3">
                        <label for="" class="form-label">Receiver </label>
                        <input type="text" id="notice_receiver" name="notice_receiver" value="{{$filterParameters['notice_receiver']}}" class="form-control">
                    </div>

                    @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                        <div class="col-lg col-md-6 mb-3">
                            <label for="" class="form-label">Published From</label>
                            <input type="text"  id="fromDate" name="publish_date_from" value="{{$filterParameters['publish_date_from']}}" placeholder="mm/dd/yyyy" class="form-control fromDate"/>
                        </div>

                        <div class="col-lg col-md-6 mb-3">
                            <label for="" class="form-label">Publish To</label>
                            <input type="text" id="toDate" name="publish_date_to" value="{{$filterParameters['publish_date_to']}}" placeholder="mm/dd/yyyy" class="form-control toDate"/>
                        </div>
                    @else
                        <div class="col-lg col-md-6 mb-3">
                            <label for="" class="form-label">Published From</label>
                            <input type="date" name="publish_date_from" value="{{$filterParameters['publish_date_from']}}" class="form-control fromDate">
                        </div>

                        <div class="col-lg col-md-6 mb-3">
                            <label for="" class="form-label">Publish To</label>
                            <input type="date"  name="publish_date_to" value="{{$filterParameters['publish_date_to']}}" class="form-control toDate">
                        </div>
                    @endif

                    <div class="col-lg-2 col-md-6 mt-3">
                        <div class="d-flex float-end">
                            <button type="submit" class="btn btn-block btn-secondary me-2">Filter</button>
                            <a class="btn btn-block btn-primary" href="{{route('admin.notices.index')}}">Reset</a>
                        </div>
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
                            <th>Publish Date</th>
                            <th>Receiver</th>
                            @can('show_notice')
                                <th class="text-center">Description</th>
                            @endcan
                            <th class="text-center">Status</th>
                            @canany(['edit_notice','delete_notice','send_notice'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @forelse($notices as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ucfirst($value->title)}}</td>
                                <td>{{ convertDateTimeFormat($value->notice_publish_date) ?? 'Not published yet'}}</td>
                                <td>
                                    <ul>
                                        @foreach(($value->noticeReceiversDetail) as $key => $datum)
                                            <li>{{ $datum->employee ? ucfirst($datum->employee->name) : 'N/A'}}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                @can('show_notice')
                                    <td class="text-center">
                                        <a href=""
                                           id="showNoticeDescription"
                                           data-href="{{route('admin.notices.show',$value->id)}}"
                                           data-id="{{ $value->id }}" title="show Notice content">
                                            <i class="link-icon" data-feather="eye"></i>
                                        </a>
                                    </td>
                                @endcan

                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.notices.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                @canany(['edit_notice','delete_notice','send_notice'])
                                    <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                        @can('edit_notice')
                                            <li class="me-2">
                                                <a href="{{route('admin.notices.edit',$value->id)}}" title="Edit notice ">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_notice')
                                            <li class="me-2">
                                                <a class="delete"
                                                   data-href="{{route('admin.notices.delete',$value->id)}}" title="Delete notice detail">
                                                    <i class="link-icon"  data-feather="delete"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('send_notice')
                                            <li>
                                                <a class="sendNotice"
                                                   data-href="{{route('admin.notices.send-notice',$value->id)}}" title="send notice">
                                                    <button class="btn btn-primary btn-xs">send notice</button>
                                                </a>
                                            </li>
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
            {{$notices->appends($_GET)->links()}}
        </div>
    </section>

    @include('admin.notice.show')
@endsection

@section('scripts')
    @include('admin.notice.common.scripts')
@endsection






