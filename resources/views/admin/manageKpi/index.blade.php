
@extends('layouts.master')

@section('title','manageKpi')

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.manageKpi.index')}}">Manage KPI section</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage KPI</li>
            </ol>

            @can('create_department')
                <a href="{{ route('admin.manageKpi.create')}}">
                    <button class="btn btn-primary add_department">
                        <i class="link-icon" data-feather="plus"></i>Add KPI
                    </button>
                </a>
            @endcan
        </nav>

        <div class="search-box p-4 bg-white rounded mb-3 box-shadow pb-2">
            <form class="forms-sample" action="{{route('admin.manageKpi.index')}}" method="get">
                <div class="row align-items-center">

                    <div class="col-lg-2 mb-3">
                        <h5>KPI Lists</h5>
                    </div>

                    <div class="col-lg-4 col-md-4 mb-3">
                        <select class="form-select form-select-lg" name="department">
                            <option value="" {{!isset($filterParameters['department']) ? 'selected': ''}}>Search by department</option>
                            @foreach($departments as $key => $value)
                                <option value="{{ $key }}" {{ (isset($filterParameters['department']) && $key == $filterParameters['department'] ) ?'selected':'' }} >
                                    {{ucfirst($value)}} </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="col-lg-2 col-md-2 mb-3">
                        <select class="form-select form-select-lg" name="per_page">
                            <option value="10" {{($filterParameters['per_page']) == 10 ? 'selected': ''}}>10</option>
                            <option value="25" {{($filterParameters['per_page']) == 25 ? 'selected': ''}}>25</option>
                            <option value="50" {{($filterParameters['per_page']) == 50 ? 'selected': ''}}>50</option>
                        </select>
                    </div> --}}

                    <div class="col-lg-2 col-md-4 d-flex">
                        <button type="submit" class="btn btn-block btn-secondary form-control me-md-2 me-0 mb-3">Filter</button>

                        <a class="btn btn-block btn-primary me-md-2 me-0 mb-3 " href="{{route('admin.manageKpi.index')}}">Reset</a>
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
                            <th>Department</th>
                            <th>KPI</th>
                            <th>Weight</th>
                            <th>Target</th>
                            <th>Min/Max</th>

                            @canany(['edit_department','delete_department'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @forelse($kpis as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ucfirst($value->department->dept_name)}}</td>
                                <td>{{ucfirst($value->kpi_desc)}}</td>
                                <td class="text-center">{{$value->weight}}</td>
                                <td>{{$value->kpi_target}} {{$value->unit}}</td>
                                <td class="text-center">{{$value->is_max ? 'max':'min'}}</td>
                                

                                @canany(['edit_kpi','delete_kpi'])
                                    <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                        @can('edit_department')
                                            <li class="me-2">
                                                <a href="{{route('admin.manageKpi.edit',$value->id)}}">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_kpi')
                                            <li>
                                                <a class="deleteBranch"
                                                   data-href="{{route('admin.manageKpi.delete',$value->id)}}">
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

        {{-- <div class="dataTables_paginate mt-3">
            {{$departments->appends($_GET)->links()}}
        </div> --}}



    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.toggleStatus').change(function (event) {
                event.preventDefault();
                var status = $(this).prop('checked') === true ? 1 : 0;
                var href = $(this).attr('href');
                Swal.fire({
                    title: 'Are you sure you want to change status ?',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    padding:'10px 50px 10px 50px',
                    // width:'500px',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }else if (result.isDenied) {
                        (status === 0)? $(this).prop('checked', true) :  $(this).prop('checked', false)
                    }
                })
            })

            $('.deleteBranch').click(function (event) {
                event.preventDefault();
                let href = $(this).data('href');
                Swal.fire({
                    title: 'Are you sure you want to Delete Department ?',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    padding:'10px 50px 10px 50px',
                    // width:'1000px',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                })
            })


        });

    </script>
@endsection
