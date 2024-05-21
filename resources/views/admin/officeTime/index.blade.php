@extends('layouts.master')

@section('title','Office Time')

@section('action','Office Time lists')

@section('button')
    @can('create_office_time')
        <a href="{{ route('admin.office-times.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>Add Office Time
            </button>
        </a>
    @endcan
@endsection

@section('main-content')

    <section class="content">


        @include('admin.section.flash_message')

        @include('admin.officeTime.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center">Opening Time</th>
                            <th class="text-center">Closing Time </th>
                            <th class="text-center">Shift</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Status</th>
                            @canany(['show_office_time','edit_office_time','delete_office_time'])
                                <th class="text-center">Action</th>
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @forelse($officeTimes as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td class="text-center">{{\App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceView($value->opening_time)}}</td>
                                <td class="text-center">{{\App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceView($value->closing_time)}}</td>
                                <td class="text-center">{{ucfirst($value->shift)}}</td>
                                <td class="text-center">{{removeSpecialChars($value->category)}}</td>

                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.office-times.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                @canany(['show_office_time','edit_office_time','delete_office_time'])
                                    <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                        @can('edit_office_time')
                                            <li class="me-2">
                                                <a href="{{route('admin.office-times.edit',$value->id)}}" title="Edit">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('show_office_time')
                                            <li class="me-2">
                                                <a href=""
                                                   id="showOfficeTimeDetail"
                                                   data-href="{{route('admin.office-times.show',$value->id)}}"
                                                   data-id="{{ $value->id }}">
                                                    <i class="link-icon" data-feather="eye"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_office_time')
                                            <li>
                                                <a class="deleteOfficeTime"
                                                   data-href="{{route('admin.office-times.delete',$value->id)}}" title="Delete">
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


    </section>
    @include('admin.officeTime.show')
@endsection

@section('scripts')

    @include('admin.officeTime.common.scripts')
@endsection

