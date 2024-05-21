@extends('layouts.master')

@section('title','Holiday')

@section('action','Lists')

@section('button')
    <div class="m-lg-1">

        @can('create_holiday')
            <a href="{{ route('admin.holidays.create')}}">
                <button class="btn btn-primary">
                    <i class="link-icon" data-feather="plus"></i>Add Holiday
                </button>
            </a>
        @endcan

        @can('import_holiday')
            <a href="{{route('admin.holidays.import-csv.show')}}">
                <button class="btn btn-success">
                    <i class="link-icon" ></i>Import Holiday CSV
                </button>
            </a>
        @endcan
    </div>

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

        @include('admin.holiday.common.breadcrumb')

        <div class="search-box p-4 bg-white rounded mb-3 box-shadow">
            <form class="forms-sample" action="{{route('admin.holidays.index')}}" method="get">
                <h5>Holiday Filter</h5>
                <div class="row align-items-center ">

                    <div class="col-lg col-md-6 mt-3">
                        <input type="text" placeholder="Event name" id="event" name="event" value="{{$filterParameters['event']}}" class="form-control">
                    </div>

                    <div class="col-lg col-md-6 mt-3">
                        <input type="number" min="{{ $filterData['min_year']}}"
                               max="{{ $filterData['max_year']}}" step="1"
                               placeholder="Leave Requested year e.g : {{$filterData['min_year']}}"
                               id="year"
                               name="event_year"
                               value="{{$filterParameters['event_year']}}"
                               class="form-control">
                    </div>

                    <div class="col-lg col-md-6 mt-3">
                        <select class="form-select form-select-lg" name="month" id="month">
                            <option value="" {{!isset($filterParameters['month']) ? 'selected': ''}} >All Month</option>
                            @foreach($months as $key => $value)
                                <option value="{{$key}}" {{ (isset($filterParameters['month']) && $key == $filterParameters['month'] ) ?'selected':'' }} >
                                    {{$value[$filterData['month']]}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 mt-3">
                        <div class="d-flex float-end">
                            <button type="submit" class="btn btn-block btn-secondary me-2">Filter</button>
                            <a class="btn btn-block btn-primary" href="{{route('admin.holidays.index')}}">Reset</a>
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
                            <th>Event</th>
                            <th>Event Date </th>
                            <th class="text-center">Status</th>
                            @canany(['show_holiday','edit_holiday','delete_holiday'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @forelse($holidays as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ucfirst($value->event)}}</td>
                                <td>{{\App\Helpers\AppHelper::formatDateForView($value->event_date)}}</td>

                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.holidays.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                @canany(['show_holiday','edit_holiday','delete_holiday'])
                                    <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">

                                        @can('edit_holiday')
                                            <li class="me-2">
                                                <a href="{{route('admin.holidays.edit',$value->id)}}" title="Edit">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('show_holiday')
                                            <li class="me-2">
                                                <a href=""
                                                   id="showHolidayDetail"
                                                   data-href="{{route('admin.holidays.show',$value->id)}}"
                                                   data-id="{{ $value->id }}">
                                                    <i class="link-icon" data-feather="eye"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_holiday')
                                            <li>
                                                <a class="deleteHoliday"
                                                   data-href="{{route('admin.holidays.delete',$value->id)}}" title="Delete">
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
            {{$holidays->appends($_GET)->links()}}
        </div>
    </section>
    @include('admin.holiday.show')
@endsection

@section('scripts')

    @include('admin.holiday.common.scripts')

@endsection

