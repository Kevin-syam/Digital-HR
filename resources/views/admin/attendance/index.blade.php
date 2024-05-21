@extends('layouts.master')

@section('title','Attendance')

@section('action','Employee Attendance Lists')


@section('main-content')

    <section class="content">
        <?php
            if(\App\Helpers\AppHelper::ifDateInBsEnabled()){
                $currentDate = \App\Helpers\AppHelper::getCurrentDateInBS();
            }else{
                $currentDate = \App\Helpers\AppHelper::getCurrentDateInYmdFormat();
            }
        ?>

        @include('admin.section.flash_message')

        @include('admin.attendance.common.breadcrumb')
        <div class="search-box p-4 pb-2 bg-white rounded mb-3 box-shadow">
            <form class="forms-sample" action="{{route('admin.attendances.index')}}" method="get">
                <h5 class="mb-3">Attendance Of The Day</h5>
                <div class="row align-items-center">

                    <div class="col-lg col-md-4 mb-3">
                        <input id="attendance_date"
                                name="attendance_date"
                                value="{{$filterParameter['attendance_date']}}"
                                @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                                    class="form-control dayAttendance"
                                    type="text"
                                    placeholder="yy/mm/dd"
                                @else
                                    class="form-control"
                                    type="date"
                                @endif
                        />
                    </div>

                    <div class="col-lg col-md-4 mb-3">
                        <select class="form-select form-select-lg" name="branch_id" id="branch_id">
                            <option value="" {{!isset($filterParameter['branch_id']) ? 'selected': ''}}>Select Branch</option>
                            @foreach($branch as $key =>  $value)
                                <option value="{{$value->id}}" {{ (isset($filterParameter['branch_id']) && $value->id == $filterParameter['branch_id'] ) ?'selected':'' }} > {{ucfirst($value->name)}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-lg-5 col-md-4 d-flex">
                        <button type="submit" class="btn btn-block btn-success form-control me-md-2 me-0 mb-3">Filter</button>

                        @can('attendance_csv_export')
                            <button type="button" id="download-daywise-attendance-excel"
                                    data-href="{{route('admin.attendances.index' )}}"
                                    class="btn btn-block btn-secondary form-control me-md-2 me-0 mb-3">CSV Export
                            </button>
                        @endcan

                        <a class="btn btn-block btn-primary me-md-2 me-0 mb-3 " href="{{route('admin.attendances.index')}}">Reset</a>

{{--                        <button type="button" class="btn btn-block btn-primary me-md-2 me-0 mb-3 reset"--}}
{{--                                data-date="{{$currentDate}}"--}}
{{--                        >Reset Filter</button>--}}
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
                            @can('attendance_show')
                                <td></td>
                            @endcan
                            <th>Employee Name</th>
                            <th class="text-center">Check In At</th>
                            <th class="text-center">Check Out At</th>
                            <th class="text-center">Attendance Status</th>
                            <th class="text-center">Attendance By</th>
                            @canany(['attendance_create','attendance_update','attendance_delete'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $changeColor = [
                            0 => 'danger',
                            1 => 'success',
                        ]
                        ?>
                        @forelse($attendanceDetail as $key => $value)
                            <tr>
                                @can('attendance_show')
                                    <td>
                                        <ul class="d-flex list-unstyled mb-0">
                                            <li class="me-2">
                                                <a href="{{route('admin.attendances.show',$value->user_id)}}"
                                                   title="show detail">
                                                    <i class="link-icon" data-feather="eye"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                @endcan

                                <td>
                                    {{ucfirst($value->user_name)}}
                                </td>

                                @if($value->check_in_at)
                                    <td class="text-center">
                                        <span class="btn btn-outline-secondary btn-xs checkLocation"
                                              title="Show check In location"
                                              data-bs-toggle="modal"
                                              data-href="https://maps.google.com/maps?q={{$value->check_in_latitude}},{{$value->check_in_longitude}}&t=&z=20&ie=UTF8&iwloc=&output=embed"
                                              data-bs-target="#addslider">
                                            {{ ($value->check_in_at) ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($value->check_in_at):''}}
                                        </span>
                                    </td>
                                @else
                                    <td class="text-center"></td>
                                @endif

                                @if($value->check_out_at)
                                    <td class="text-center">
                                        <span class="btn btn-outline-secondary btn-xs checkLocation"
                                              title="Show checkout location"
                                              data-bs-toggle="modal"
                                              data-href="https://maps.google.com/maps?q={{$value->check_out_latitude}},{{$value->check_out_longitude}}&t=&z=20&ie=UTF8&iwloc=&output=embed"
                                              data-bs-target="#addslider">
                                           {{  ($value->check_out_at) ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($value->check_out_at)  : ''}}
                                        </span>
                                    </td>
                                @else
                                    <td class="text-center"></td>
                                @endif


                                @if(!is_null($value->attendance_status))
                                    <td class="text-center">
                                        <a class="changeAttendanceStatus btn btn-{{$changeColor[$value->attendance_status]}} btn-xs"
                                           data-href="{{route('admin.attendances.change-status',$value->attendance_id)}}" title="Change Attendance Status">
                                            {{($value->attendance_status == \App\Models\Attendance::ATTENDANCE_APPROVED) ? 'Approved':'Rejected'}}
                                        </a>
                                    </td>
                                @else
                                    <td class="text-center">
                                       <span class="btn btn-light btn-xs disabled">
                                            Pending
                                        </span>
                                    </td>
                                @endif

                                @if($value->created_by)
                                    <td class="text-center">
                                        <span class="btn btn-warning btn-xs">
                                            {{ ($value->user_id == $value->created_by )  ? 'Self' : 'Admin'}}
                                        </span>
                                    </td>
                                @else
                                    <td class="text-center">

                                    </td>
                                @endif

                            @canany(['attendance_create','attendance_update','attendance_delete'])
                                <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                        @if($filterParameter['attendance_date'] ==  $currentDate)
                                            @if(!$value->check_in_at)
                                                @can('attendance_create')
                                                    <li class="me-2">
                                                        <a href="{{route('admin.employees.check-in',[$value->company_id,$value->user_id,])}}"
                                                           id="checkIn"
                                                           data-href=""
                                                           data-id="">
                                                            <button class="btn btn-success btn-xs">Check In</button>
                                                        </a>
                                                    </li>
                                                @endcan
                                            @endif

                                            @if($value->check_in_at && !$value->check_out_at)
                                                @can('attendance_update')
                                                    <li class="me-2">
                                                        <a href="{{route('admin.employees.check-out',[ $value->company_id,$value->user_id])}}"
                                                           id="checkOut"
                                                           data-href=""
                                                           data-id="">
                                                            <button class="btn btn-danger btn-xs">Check Out</button>
                                                        </a>
                                                    </li>
                                                @endcan
                                            @endif
                                        @endif

                                        @if($value->attendance_id)
                                            @can('attendance_update')
                                                <li class="me-2">
                                                    <a
                                                        href=""
                                                        class="editAttendance"
                                                        data-href="{{route('admin.attendances.update',$value->attendance_id)}}"
                                                        data-in="{{ date('H:i',strtotime($value->check_in_at))}}"
                                                        data-out="{{ ($value->check_out_at) ? date('H:i',strtotime($value->check_out_at)) : null}}"
                                                        data-remark="{{$value->edit_remark}}"
                                                        data-date="{{$filterParameter['attendance_date']}}"
                                                        data-name="{{ucfirst($value->user_name)}}"
                                                        title="Edit attendance time"
                                                    >
                                                        <i class="link-icon" data-feather="edit"></i>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('attendance_delete')
                                                <li class="me-2">
                                                    <a class="deleteAttendance" href="{{route('admin.attendance.delete',$value->attendance_id)}}">
                                                        <i class="link-icon"  data-feather="delete"></i>
                                                    </a>
                                                </li>
                                            @endcan
                                        @endif
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


        <div class="modal fade" id="addslider" tabindex="-1" aria-labelledby="addslider" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <iframe id="iframeModalWindow" class="attendancelocation" height="500px" width="100%" src="" name="iframe_modal"></iframe>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.attendance.common.edit-attendance-form')

    </section>
@endsection

@section('scripts')
    @include('admin.attendance.common.scripts')
@endsection

