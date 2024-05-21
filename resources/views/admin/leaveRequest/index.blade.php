
@extends('layouts.master')

@section('title','Leave Requests')

@section('action','Lists')

@section('button')

@endsection

@section('main-content')
    <?php
    if(\App\Helpers\AppHelper::ifDateInBsEnabled()){
        $filterData['min_year'] = '2076';
        $filterData['max_year'] = '2089';
        $filterData['month'] = 'np';
    }else{
        $filterData['min_year'] = '2020';
        $filterData['max_year'] = '2033';
        $filterData['month'] = 'en';
    }
    ?>

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.leaveRequest.common.breadcrumb')
        <div class="search-box p-4 bg-white rounded mb-3 box-shadow">
            <form class="forms-sample" action="{{route('admin.leave-request.index')}}" method="get">

                <h5>Leave Request Filter</h5>

                <div class="row align-items-center">

                    <div class="col-xxl col-xl-4 col-md-6 mt-3">
                        <input type="text" placeholder="Requested by" id="requestedBy" name="requested_by" value="{{$filterParameters['requested_by']}}" class="form-control">
                    </div>

                    <div class="col-xxl col-xl-4 col-md-6 mt-3">
                        <select class="form-select form-select-lg" name="leave_type" id="leaveType">
                            <option value="" {{!isset($filterParameters['leave_type']) ? 'selected': ''}}   >All Leave Type</option>
                            @foreach($leaveTypes as $key => $value)
                                <option value="{{$value->id}}" {{ (isset($filterParameters['leave_type']) && $value->id == $filterParameters['leave_type'] ) ?'selected':'' }} > {{($value->name)}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-4 col-md-6 mt-3">
                        <input type="number" min="{{ $filterData['min_year']}}"
                               max="{{ $filterData['max_year']}}" step="1"
                               placeholder="Leave Requested year e.g : {{$filterData['min_year']}}"
                               id="year"
                               name="year" value="{{$filterParameters['year']}}"
                               class="form-control">
                    </div>

                    <div class="col-xxl col-xl-4 col-md-6 mt-3">
                        <select class="form-select form-select-lg" name="month" id="month">
                            <option value="" {{!isset($filterParameters['month']) ? 'selected': ''}} >All Month</option>
                            @foreach($months as $key => $value)
                                <option value="{{$key}}" {{ (isset($filterParameters['month']) && $key == $filterParameters['month'] ) ?'selected':'' }} >
                                    {{$value[$filterData['month']]}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-4 col-md-6 mt-3">
                        <select class="form-select form-select-lg" name="status" id="status">
                            <option value="" {{!isset($filterParameters['status']) ? 'selected': ''}}   >All Status</option>
                            @foreach(\App\Models\LeaveRequestMaster::STATUS as  $value)
                                <option value="{{$value}}" {{ (isset($filterParameters['status']) && $value == $filterParameters['status'] ) ?'selected':'' }} > {{ucfirst($value)}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-4 mt-3">
                        <div class="d-flex float-end">
                            <button type="submit" class="btn btn-block btn-secondary me-2">Filter</button>
                            <a class="btn btn-block btn-primary" href="{{route('admin.leave-request.index')}}">Reset</a>
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
                            <th>Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Requested Date</th>
                            <th>Requested By</th>
                            <th class="text-center">Requested Days</th>
                            @can('show_leave_request_detail')
                                <th class="text-center">Reason</th>
                            @endcan
                            @can('update_leave_request')
                                <th class="text-center">Status</th>
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <?php
                            $color = [
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'pending' => 'secondary',
                                'cancelled' => 'danger'
                            ]
                        ?>
                        @forelse($leaveDetails as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ $value->leaveType ? ucfirst($value->leaveType->name) : 'N/A'}}</td>
                                <td>{{\App\Helpers\AppHelper::convertLeaveDateFormat($value->leave_from)}}</td>
                                <td>{{\App\Helpers\AppHelper::dateInDDMMFormat($value->leave_to)}}</td>
                                <td>{{\App\Helpers\AppHelper::formatDateForView($value->leave_requested_date)}}</td>
                                <td>{{$value->leaveRequestedBy ? ucfirst($value->leaveRequestedBy->name) : 'N/A'}} </td>
                                <td class="text-center">{{($value->no_of_days )}}</td>

                                @can('show_leave_request_detail')
                                    <td class="text-center">
                                        <a href=""
                                           id="showLeaveReason"
                                           data-href="{{route('admin.leave-request.show',$value->id) }}"
                                           data-id="" title="show leave reason ">
                                            <i class="link-icon" data-feather="eye"></i>
                                        </a>
                                    </td>
                                @endcan

                                @can('update_leave_request')
                                    <td class="text-center">
                                        <a href=""
                                           id="leaveRequestUpdate"
                                           data-href="{{route('admin.leave-request.update-status',$value->id)}}"
                                           data-status="{{$value->status}}"
                                           data-remark="{{$value->admin_remark}}"
                                        >
                                            <button class="btn btn-{{ $color[$value->status] }} btn-xs">
                                                {{ucfirst($value->status)}}
                                            </button>
                                        </a>
                                    </td>
                                @endcan
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
    </section>
    <div class="dataTables_paginate mt-3">
        {{$leaveDetails->appends($_GET)->links()}}
    </div>

    @include('admin.leaveRequest.show')
    @include('admin.leaveRequest.common.form-model')
@endsection

@section('scripts')
    @include('admin.leaveRequest.common.scripts')
@endsection






