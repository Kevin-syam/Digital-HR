@extends('layouts.master')

@section('title','Support')

@section('action','Query Lists')


@section('main-content')


    <section class="content">

        @include('admin.section.flash_message')
        <div id="showFlashMessageResponse">
            <div class="alert alert-danger error d-none">
                <p class="errorMessageDelete"></p>
            </div>
        </div>

        @include('admin.support.common.breadcrumb')

        <div class="search-box p-4  bg-white rounded mb-3 box-shadow pb-2">

            <form class="forms-sample" action="{{route('admin.supports.index')}}" method="get">
                <h5 class="mb-3">Support Filter</h5>

                <div class="row align-items-center ">

                    <div class="col-lg col-md-6 mb-3">
                        <label for="" class="form-label">Query Status</label>
                        <select class="form-select form-select-lg" name="status" id="status">
                            <option value="" {{!isset($filterParameters['status']) ? 'selected': ''}} >All</option>
                            @foreach(\App\Models\Support::STATUS as $value)
                                <option value="{{$value}}" {{isset($filterParameters['status']) && $filterParameters['status'] == $value  ? 'selected': ''}} >
                                    {{removeSpecialChars($value)}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg col-md-6 mb-3">
                        <label for="" class="form-label">Read Status</label>
                        <select class="form-select form-select-lg" name="is_seen" id="is_seen">
                            <option value="" {{!isset($filterParameters['is_seen']) ? 'selected': ''}} >All</option>
                            <option value="0" {{isset($filterParameters['is_seen']) && $filterParameters['is_seen'] == 0 ? 'selected': ''}} >Unseen</option>
                            <option value="1" {{isset($filterParameters['is_seen']) && $filterParameters['is_seen'] == 1 ? 'selected': ''}} >Seen</option>
                        </select>
                    </div>

                    @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                        <div class="col-lg col-md-6 mb-3">
                            <label for="" class="form-label">From Date</label>
                            <input type="text"  id="nepali-datepicker-from" name="query_from" value="{{$filterParameters['query_from']}}" placeholder="mm/dd/yyyy" class="form-control queryFrom"/>
                        </div>

                        <div class="col-lg col-md-6 mb-3">
                            <label for="" class="form-label">To Date</label>
                            <input type="text" id="nepali-datepicker-to" name="query_to" value="{{$filterParameters['query_to']}}" placeholder="mm/dd/yyyy" class="form-control queryTo"/>
                        </div>
                    @else
                        <div class="col-lg col-md-6 mb-3">
                            <label for="" class="form-label">From Date</label>
                            <input type="date"  value="{{$filterParameters['query_from']}}" name="query_from" class="form-control">
                        </div>

                        <div class="col-lg col-md-6 mb-3">
                            <label for="" class="form-label">To Date</label>
                            <input type="date"  value="{{$filterParameters['query_to']}}" name="query_to" class="form-control">
                        </div>
                    @endif

                    <div class="col-lg-12 mb-3">
                        <div class="d-flex float-end">
                            <button type="submit" class="btn btn-block btn-secondary me-2">Filter</button>
                            <a class="btn btn-block btn-primary" href="{{route('admin.supports.index')}}">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card support-main">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Date </th>
                            <th>Query By </th>
                            <th>Branch</th>
                            <th>Concerned Department </th>
                            <th class="text-center">Status</th>
                            @canany(['show_query_detail','delete_query'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $statusColor = [
                                'pending' => 'secondary',
                                'in_progress' => 'warning',
                                'solved' => 'success',
                            ]
                        ?>
                        <tr>

                        @forelse($supportQueries as $key => $value)
                            <tr class="status{{$value->id}}"
                                style="
                                    font-weight: {{!$value->is_seen ? 'bold' : ''}} ;
                                    background:{{!$value->is_seen ? '#f9f9f9' : ''}};
                                    ">
                                <td>{{++$key}}</td>
                                <td >{{ucfirst($value->title)}}</td>
                                <td>
                                    {{\App\Helpers\AppHelper::formatDateForView($value->created_at)}}
                                </td>
                                <td>{{ucfirst($value->createdBy->name)}}</td>
                                <td>{{ucfirst($value->createdBy?->branch?->name)}}</td>
{{--                                <td>{{ucfirst($value->createdBy?->department?->dept_name)}}</td>--}}
                                <td>{{ucfirst($value->departmentQuery?->dept_name)}}</td>
                                <td class="text-center">
                                    <span class="cursor-default btn btn-xs white btn-{{$statusColor[$value->status]}}">
                                       {{removeSpecialChars($value->status)}}
                                    </span>
                                </td>
                                @canany(['show_query_detail','delete_query'])
                                    <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">

                                        @can('show_query_detail')
                                            <li class="me-2">
                                                <a href=""
                                                   data-href="{{route('admin.supports.changeSeenStatus',$value->id)}}"
                                                   id="showDetail"
                                                   data-id="{{ $value->id }}"
                                                   data-branch="{{$value->createdBy?->branch?->name}}"
                                                   data-department="{{$value->createdBy?->department?->dept_name}}"
                                                   data-requested="{{$value->departmentQuery?->dept_name}}"
                                                   data-description="{{$value->description}}"
                                                   data-title="{{$value?->title}}"
                                                   data-status="{{removeSpecialChars($value?->status)}}"
                                                   data-submitted="{{$value->createdBy?->name}}"
                                                   data-action="{{route('admin.supports.updateStatus',$value->id)}}"
                                                >
                                                    <i class="link-icon" data-feather="eye"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_query')
                                            <li>
                                                <a class="delete"
                                                   data-title="Query"
                                                   data-href="{{route('admin.supports.delete',$value->id)}}"
                                                   title="Delete">
                                                    <i class="link-icon"  data-feather="delete"></i>
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
            {{$supportQueries->appends($_GET)->links()}}
        </div>
    </section>
    @include('admin.support.show')
@endsection

@section('scripts')
    @include('admin.support.common.scripts')
@endsection

