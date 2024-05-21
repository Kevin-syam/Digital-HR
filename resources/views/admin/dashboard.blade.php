@extends('layouts.master')

@section('title','Digital HR Dashboard')

<?php
    $attendanceDetail = (\App\Helpers\AppHelper::employeeTodayAttendanceDetail());
    $checkInAt = $attendanceDetail['check_in_at'] ?? '';
    $checkOutAt = $attendanceDetail['check_out_at'] ?? '';
    $attendanceDate = $attendanceDetail['attendance_date'] ?? '';
    $viewCheckIn = $checkInAt ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($checkInAt) : '-:-:-';
    $viewCheckOut = $checkOutAt ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($checkOutAt) : '-:-:-';
?>

@section('nav-head','Welcome to Attendance Dashboard : ' .ucfirst($dashboardDetail?->company_name) )

@section('styles')
    <style>
        #clockContainer {
            background: url({{asset('assets/images/clock.jpg') }}) no-repeat;
            background-size: 100%;
        }
    </style>
@endsection

@section('main-content')

    <section class="content">
        <?php
            $projectPriority = [
                'low' => 'info',
                'medium' => 'warning',
                'high' => 'primary',
                'urgent' => 'primary'
            ];
        ?>

        <div id="flashAttendanceMessage" class="d-none">
            <div class="alert alert-danger errorStartWorking">
                <p class="errorStartWorkingMessage"></p>
            </div>

            <div class="alert alert-danger errorStopWorking">
                <p class="errorStopWorkingMessage"></p>
            </div>

            <div class="alert alert-success successStartWorking">
                <p class="successStartWorkingMessage"></p>
            </div>

            <div class="alert alert-success successStopWorking">
                <p class="successStopWorkingMessage"></p>
            </div>
        </div>

        <div id="loader" style="display:none;">
            <div class="loading">
                <div class="loading-content"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-9 col-xl-8 d-flex">
                <div class="row">
                    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 mb-4 d-flex">
                        <div class="card w-100">
                            <div class="card-body text-md-start text-center">
                                <div class="d-md-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title">Total Employees</h6>
                                </div>

                                <div class="row align-items-center d-md-flex">
                                    <div class="col-lg-6 col-md-6">
                                        <h3>{{number_format($dashboardDetail?->total_employee)}}</h3>
                                    </div>
                                    <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                        <i class="link-icon" data-feather="users"> </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 mb-4 d-flex">
                        <div class="card w-100">
                            <div class="card-body text-md-start text-center">
                                <div class="d-md-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title">Total Departments</h6>
                                </div>
                                <div class="row align-items-center d-md-flex">
                                    <div class="col-lg-6 col-md-6">
                                        <h3>{{number_format($dashboardDetail?->total_departments)}}</h3>
                                    </div>
                                    <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                        <i class="link-icon" data-feather="layers"> </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-4 mb-4 d-flex">
                        <div class="card w-100">
                            <div class="card-body text-md-start text-center">
                                <div class="d-md-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title">Total Holidays</h6>
                                </div>
                                <div class="row align-items-center d-md-flex">
                                    <div class="col-lg-6 col-md-6">
                                        <h3>{{number_format($dashboardDetail?->total_holidays) ?? 0}}</h3>
                                    </div>
                                    <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                        <i class="link-icon" data-feather="umbrella"> </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-4 mb-4 d-flex">
                        <div class="card w-100">
                            <div class="card-body text-md-start text-center">
                                <div class="d-md-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title">Paid Leaves</h6>
                                </div>
                                <div class="row align-items-center d-md-flex">
                                    <div class="col-lg-6 col-md-6">
                                        <h3>{{number_format($dashboardDetail?->total_paid_leaves) ?? 0}}</h3>
                                    </div>
                                    <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                        <i class="link-icon" data-feather="file-text"> </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-4 mb-4 d-flex">
                        <div class="card w-100">
                            <div class="card-body text-md-start text-center">
                                <div class="d-md-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title">On Leave Today</h6>
                                </div>
                                <div class="row align-items-center d-md-flex">
                                    <div class="col-lg-6 col-md-6">
                                        <h3>{{number_format($dashboardDetail?->total_on_leave) ?? 0}}</h3>
                                    </div>
                                    <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                        <i class="link-icon" data-feather="file-minus"> </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-4 mb-4 d-flex">
                        <div class="card w-100">
                            <div class="card-body text-md-start text-center">
                                <div class="d-md-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title">Pending Leave Requests</h6>
                                </div>
                                <div class="row align-items-center d-md-flex">
                                    <div class="col-lg-6 col-md-6">
                                        <h3>{{ number_format($dashboardDetail?->total_pending_leave_requests) ?? 0}}</h3>
                                    </div>
                                    <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                        <i class="link-icon" data-feather="twitch"> </i>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-4 mb-4 d-flex">
                        <div class="card w-100">
                            <div class="card-body text-md-start text-center">
                                <div class="d-md-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title">Total Check In Today</h6>
                                </div>
                                <div class="row align-items-center d-md-flex">
                                    <div class="col-lg-6 col-md-6">
                                        <h3>{{number_format($dashboardDetail?->total_checked_in_employee) ?? 0 }}</h3>
                                    </div>
                                    <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                        <i class="link-icon" data-feather="log-in"> </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-4 mb-4 d-flex">
                        <div class="card w-100">
                            <div class="card-body text-md-start text-center">
                                <div class="d-md-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title">Total Check Out Today</h6>
                                </div>
                                <div class="row align-items-center d-md-fle">
                                    <div class="col-lg-6 col-md-6">
                                        <h3>{{number_format($dashboardDetail?->total_checked_out_employee) ?? 0 }}</h3>
                                    </div>
                                    <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                        <i class="link-icon" data-feather="log-out"> </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @can('allow_attendance')
                <div class="col-xxl-3 col-xl-4 mb-4 d-flex">
                    <div class="card w-100">
                        <div class="card-body text-center clock-display">
                            <div id="clockContainer" class="mb-3">
                                <div id="hour"></div>
                                <div id="minute"></div>
                                <div id="second"></div>
                            </div>

                            <p id="date" class="text-primary fw-bolder mb-3"></p>

                            <div class="punch-btn mb-2 d-flex align-items-center justify-content-around">
                                <button href="{{route('admin.dashboard.takeAttendance','checkIn')}}"
                                        class="btn btn-lg btn-success  {{ $checkInAt ? 'd-none' : ''}}"
                                        id="startWorkingBtn" data-audio="{{asset('assets/audio/beep.mp3')}}"
                                >
                                    Punch In
                                </button>
                                <button href="{{route('admin.dashboard.takeAttendance','checkOut')}}"
                                        class="btn btn-lg btn-danger {{ $checkOutAt ? 'd-none' : ''}}"
                                        id="stopWorkingBtn" data-audio="{{asset('assets/audio/beep.mp3')}}"
                                >
                                    Punch Out
                                </button>
                            </div>

                            <div class="check-text d-flex align-items-center justify-content-around">
                                <span >Check In At<p class="text-success fw-bold h5" id="checkInTime">{{$viewCheckIn}} </p></span>
                                <span >Check Out At<p class="text-danger fw-bold h5" id="checkOutTime">{{$viewCheckOut}}  </p></span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
        @canany(['project_detail','client_detail'])
            @can('project_detail')
                <div class="projectManagement">
                    <h4 class="mb-4">Project Management </h4>
                    <div class="row">
                        <div class="col-xxl-6 col-xl-6 d-flex mb-4">
                            <div class="card card-table flex-fill">
                                <div class="card-header">
                                    <h3 class="card-title mb-0">Projects Detail</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="projectChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-xl-6 d-flex">
                            <div class="row">
                                <div class="col-xxl-6 col-xl-6 col-lg-4 col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body text-md-start text-center">
                                            <h6 class="card-title mb-2">Total Projects</h6>
                                            <div class="row align-items-center d-md-flex">
                                                <div class="col-lg-6 col-md-6">
                                                    <h3>{{number_format($projectCardDetail['total_projects'])}}</h3>
                                                </div>
                                                <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                                    <i class="link-icon" data-feather="layers"> </i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-xl-6 col-lg-4 col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body text-md-start text-center">
                                            <h6 class="card-title mb-2">Pending Projects</h6>
                                            <div class="row align-items-center d-md-flex">
                                                <div class="col-lg-6 col-md-6">
                                                    <h3>{{number_format($projectCardDetail['not_started'])}}</h3>
                                                </div>
                                                <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                                    <i class="link-icon" data-feather="layers"> </i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-xl-6 col-lg-4 col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body text-md-start text-center">
                                            <h6 class="card-title mb-2">On Hold Projects</h6>
                                            <div class="row align-items-center d-md-flex">
                                                <div class="col-lg-6 col-md-6">
                                                    <h3>{{number_format($projectCardDetail['on_hold'])}}</h3>
                                                </div>
                                                <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                                    <i class="link-icon" data-feather="layers"> </i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-xl-6 col-lg-4 col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body text-md-start text-center">
                                            <h6 class="card-title mb-2">In Progress Projects</h6>
                                            <div class="row align-items-center d-md-flex">
                                                <div class="col-lg-6 col-md-6">
                                                    <h3>{{number_format($projectCardDetail['in_progress'])}}</h3>
                                                </div>
                                                <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                                    <i class="link-icon" data-feather="layers"> </i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-xl-6 col-lg-4 col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body text-md-start text-center">
                                            <h6 class="card-title mb-2">Finished Projects</h6>
                                            <div class="row align-items-center d-md-flex">
                                                <div class="col-lg-6 col-md-6">
                                                    <h3>{{number_format($projectCardDetail['completed'])}}</h3>
                                                </div>
                                                <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                                    <i class="link-icon" data-feather="layers"> </i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-xl-6 col-lg-4 col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body text-md-start text-center">
                                            <h6 class="card-title mb-2">Cancelled Projects</h6>
                                            <div class="row align-items-center d-md-flex">
                                                <div class="col-lg-6 col-md-6">
                                                    <h3>{{number_format($projectCardDetail['cancelled'])}}</h3>
                                                </div>
                                                <div class="col-lg-6 col-md-6 text-md-end dash-icon mt-md-0 mt-2">
                                                    <i class="link-icon" data-feather="layers"> </i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            <div class="row">
                @can('client_detail')
                    <div class="col-xxl-8 col-xl-8 mb-4 d-flex">
                        <div class="card card-table flex-fill">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h3 class="card-title mb-0">Top Clients</h3>
                                <a href="{{route('admin.clients.index')}}">View All Clients</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Contact</th>
                                                <th class="text-center">Project</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topClients as $key => $client)
                                                <tr>
                                                    <td class="table-avatar w-35">

                                                            <a href="{{route('admin.clients.show',$client->id)}}" class="avatar">
                                                                <img alt=""  src="{{asset(\App\Models\Client::UPLOAD_PATH.$client->avatar)}}">
                                                                <span class="ms-1">{{ucfirst($client->name)}}</span>
                                                            </a>

                                                    </td>
                                                    <td>{{$client->email}}</td>
                                                    <td>
                                                        {{$client->contact_no}}
                                                    </td>

                                                    <td class="text-center">
                                                        {{$client->project_count}}
                                                    </td>
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
                @endcan

                @can('project_detail')
                    <div class="col-xxl-4 col-xl-4 mb-4 d-flex">
                        <div class="card card-table flex-fill">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Task Details</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="tasksChart"></canvas>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>

            @can('project_detail')
                    <div class="card card-table flex-fill">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">Recent Projects</h3>
                            <a href="{{route('admin.projects.index')}}">View All Projects</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table mb-0">
                                    <thead>
                                    <tr>
                                        <th class="w-25">Title</th>
                                        <th>Date Start</th>
                                        <th>Deadline</th>
                                        <th class="text-center">Leader</th>
                                        <th class="text-center">Completion</th>
                                        <th class="text-center">Priority</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($recentProjects as $key => $project)
                                        <tr>
                                            <td class="w-25">
                                                <a href="{{route('admin.projects.show',$project->id)}}" >{{ucfirst($project->name)}} </a>
                                            </td>
                                            <td>{{\App\Helpers\AppHelper::formatDateForView($project->start_date)}}</td>
                                            <td>
                                                {{\App\Helpers\AppHelper::formatDateForView($project->deadline)}}
                                            </td>

                                            <td class="member-listed text-center">
                                                @forelse($project->projectLeaders as $key => $leader)
                                                    <button type="button" class="p-0 border-0 bg-transparent ms-n3 " disabled data-toggle="tooltip" data-placement="top" title="{{ucfirst($leader->user->name)}}">
                                                        <img class="rounded-circle" style="object-fit: cover"
                                                             src="{{ $leader->user->avatar ? asset(\App\Models\User::AVATAR_UPLOAD_PATH.$leader->user->avatar):
                                                                        asset('assets/images/img.png')
                                                            }}"
                                                             alt="profile">
                                                    </button>
                                                @empty

                                                @endforelse
                                            </td>
                                            <td class="text-center">
                                                <div class="progress">
                                                    <div class="progress-bar color2 rounded"
                                                         role="progressbar"
                                                         style="{{\App\Helpers\AppHelper::getProgressBarStyle($project->getProjectProgressInPercentage())}}"
                                                         aria-valuenow="25"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100" >
                                                        <span>{{($project->getProjectProgressInPercentage())}} %</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                    <span class="btn btn-{{$projectPriority[$project->priority]}} btn-xs cursor-default">
                                                            {{ucfirst($project->priority)}}
                                                    </span>
                                            </td>
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
            @endcan
        @endcanany
    </section>
@endsection

<script src="{{asset('assets/vendors/chartjs/Chart.min.js')}}"></script>

@section('scripts')
    @include('admin.dashboard_scripts')
@endsection









