@extends('layouts.master')

@section('title','Attendance')

@section('action','Employee Attendance Detail')

@section('button')
    <a href="{{route('admin.attendances.index')}}" >
        <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
    </a>
@endsection

@section('main-content')
    <?php
        if(\App\Helpers\AppHelper::ifDateInBsEnabled()){
            $filterData['min_year'] = '2076';
            $filterData['max_year'] = '2089';
            $filterData['month'] = 'np';
            $nepaliDate = \App\Helpers\AppHelper::getCurrentNepaliYearMonth();
            $filterData['current_year'] = $nepaliDate['year'];
            $filterData['current_month'] = $nepaliDate['month'];
        }else{
            $filterData['min_year'] = '2020';
            $filterData['max_year'] = '2033';
            $filterData['current_year'] = now()->format('Y');
            $filterData['current_month'] = now()->month;
            $filterData['month'] = 'en';
        }
    ?>

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.attendance.common.breadcrumb')
        <div class="search-box p-4 bg-white rounded mb-3 box-shadow">
            <h5>Attendance Of {{ucfirst($userDetail->name)}}</h5>
            <form class="forms-sample" action="{{route('admin.attendances.show',$userDetail->id )}}" method="get">
                <div class="row align-items-center mt-3">
                    <div class="col-lg-3 col-md-3">
                        <input type="number" min="{{ $filterData['min_year']}}"
                               max="{{ $filterData['max_year']}}" step="1"
                               placeholder="Attendance year e.g : {{$filterData['min_year']}}"
                               id="year"
                               name="year"
                               value="{{$filterParameter['year']}}"
                               class="form-control">
                    </div>

                    <div class="col-lg-3 col-md-3">
                        <select class="form-select form-select-lg" name="month" id="month">
                            <option value="" {{!isset($filterParameter['month']) ? 'selected': ''}} >All Month</option>
                            @foreach($months as $key => $value)
                                <option value="{{$key}}" {{ (isset($filterParameter['month']) && $key == $filterParameter['month'] ) ?'selected':'' }} >
                                    {{$value[$filterData['month']]}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-3">
                        <button type="submit" class="btn btn-block btn-success form-control">Filter</button>
                    </div>

                    @can('attendance_csv_export')
                        <div class="col-lg-2 col-md-3">
                            <button type="button" id="download-excel" data-href="{{route('admin.attendances.show',$userDetail->id )}}"
                                    class="btn btn-block btn-secondary form-control">
                                CSV Export
                            </button>
                        </div>
                    @endcan

                    <div class="col-lg-2 col-md-3">
                        <a class="btn btn-block btn-primary" href="{{route('admin.attendances.show',$userDetail->id )}}">Reset</a>
{{--                        <button type="button" class="btn btn-block btn-primary  detailReset"--}}
{{--                                data-year="{{$filterData['current_year']}}"--}}
{{--                                data-month="{{$filterData['current_month']}}"--}}
{{--                        >Reset</button>--}}
                    </div>

                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                <tr>
                                    <th> Date </th>
                                    <th style="text-align: center;" >Check In At</th>
                                    <th style="text-align: center;" >Check Out At</th>
                                    <th style="text-align: center;">Worked Hour</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: center;">Attendance By</th>
                                    @can('attendance_update')
                                        <th style="text-align: center;">Action</th>
                                    @endcan
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

                                            <td>{{\App\Helpers\AttendanceHelper::formattedAttendanceDate($value['attendance_date'])}} ({{ \App\Helpers\AppHelper::weekDay($value['attendance_date']) }})</td>

                                            @if(isset($value['check_in_at']))

                                                @if($value['check_in_at'])
                                                    <td class="text-center">
                                                        <span class="btn btn-outline-secondary btn-xs checkLocation"
                                                              title="Show check In location"
                                                              data-bs-toggle="modal"
                                                              data-href="https://maps.google.com/maps?q={{$value['check_in_latitude']}},{{$value['check_in_longitude']}}&t=&z=20&ie=UTF8&iwloc=&output=embed"
                                                              data-bs-target="#addslider">
                                                            {{ ($value['check_in_at']) ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($value['check_in_at']):''}}
                                                        </span>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif

                                                @if($value['check_out_at'])
                                                    <td class="text-center">
                                                        <span class="btn btn-outline-secondary btn-xs checkLocation"
                                                              title="Show checkout location"
                                                              data-bs-toggle="modal"
                                                              data-href="https://maps.google.com/maps?q={{$value['check_out_latitude']}},{{$value['check_out_longitude']}}&t=&z=20&ie=UTF8&iwloc=&output=embed"
                                                              data-bs-target="#addslider">
                                                           {{  ($value['check_out_at']) ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($value['check_out_at']) : ''}}
                                                        </span>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif

                                                <td class="text-center">
                                                    @if($value['check_out_at'])
                                                        {{\App\Helpers\AttendanceHelper::getWorkedHourInHourAndMinute($value['check_in_at'],$value['check_out_at'])}}
                                                    @endif
                                                 </td>

                                                @if(!is_null($value['attendance_status']))
                                                    <td class="text-center">
                                                        <a class="changeAttendanceStatus btn btn-{{$changeColor[$value['attendance_status']]}} btn-xs"
                                                           data-href="{{route('admin.attendances.change-status',$value['id'])}}" title="Change Attendance Status">
                                                            {{($value['attendance_status'] == \App\Models\Attendance::ATTENDANCE_APPROVED) ? 'Approved':'Rejected'}}
                                                        </a>
                                                    </td>
                                                @else
                                                    <td>
                                                           <span class="btn btn-light btn-xs disabled">
                                                                Pending
                                                            </span>
                                                    </td>
                                                @endif

                                                @if($value['created_by'])
                                                    <td class="text-center">
                                                            <span class="btn btn-warning btn-xs">
                                                                {{ ($value['user_id'] == $value['created_by'] )  ? 'Self' : 'Admin'}}
                                                            </span>
                                                    </td>
                                                @else
                                                    <td>

                                                    </td>
                                                @endif
                                            @else

                                                <td class="text-center"> <i class="link-icon" data-feather="x"></i></td>
                                                <td class="text-center"> <i class="link-icon" data-feather="x"></i></td>
                                                <td class="text-center"> <i class="link-icon" data-feather="x"></i></td>
                                                <?php
                                                    $reason = (\App\Helpers\AttendanceHelper::getHolidayOrLeaveDetail($value['attendance_date'], $userDetail->id));
                                                ?>
                                                @if($reason)
                                                    <td class="text-center">
                                                        <span class="btn btn-outline-secondary btn-xs">
                                                            {{$reason}}
                                                        </span>
                                                    </td>
                                                @endif
                                                <td class="text-center"> <i class="link-icon" data-feather="x"></i></td>
                                            @endif

                                            @can('attendance_update')
                                                <td class="text-center">
                                                <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                                    @if(isset($value['id']))
                                                        <li class="me-2">
                                                            <a
                                                                href=""
                                                                class="editAttendance"
                                                                data-href="{{route('admin.attendances.update',$value['id'])}}"
                                                                data-in="{{ date('H:i',strtotime($value['check_in_at']))}}"
                                                                data-out="{{ ($value['check_out_at']) ? date('H:i',strtotime($value['check_out_at'])) : null}}"
                                                                data-remark="{{$value['edit_remark']}}"
                                                                data-date="{{$value['attendance_date']}} ({{ \App\Helpers\AppHelper::weekDay($value['attendance_date']) }})"
                                                                data-name="{{ucfirst($userDetail->name)}}"
                                                                title="Edit attendance time"
                                                            >
                                                                <i class="link-icon" data-feather="edit"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </td>
                                            @endcan
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

