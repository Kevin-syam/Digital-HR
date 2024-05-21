
@extends('layouts.master')

@section('title','Department')

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.departments.index')}}">Department section</a></li>
                <li class="breadcrumb-item active" aria-current="page">Departments</li>
            </ol>

            @can('create_department')
                <a href="{{ route('admin.departments.create')}}">
                    <button class="btn btn-primary add_department">
                        <i class="link-icon" data-feather="plus"></i>Add Department
                    </button>
                </a>
            @endcan
        </nav>

        <div class="search-box p-4 bg-white rounded mb-3 box-shadow pb-2">
            <form class="forms-sample" action="{{route('admin.departments.index')}}" method="get">
                <div class="row align-items-center">

                    <div class="col-lg-2 mb-3">
                        <h5>Department Lists</h5>
                    </div>

                    <div class="col-lg-3 col-md-3 mb-3">
                        <select class="form-select form-select-lg" name="branch">
                            <option value="" {{!isset($filterParameters['branch']) ? 'selected': ''}}>Select Branch</option>
                            @foreach($branch as $key => $value)
                                <option value="{{ $value->id }}" {{ (isset($filterParameters['branch']) && $value->id == $filterParameters['branch'] ) ?'selected':'' }} >
                                    {{ucfirst($value->name)}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-3 mb-3">
                        <input type="text" placeholder="Search by Department name" name="name" value="{{$filterParameters['name']}}" class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-2 mb-3">
                        <select class="form-select form-select-lg" name="per_page">
                            <option value="10" {{($filterParameters['per_page']) == 10 ? 'selected': ''}}>10</option>
                            <option value="25" {{($filterParameters['per_page']) == 25 ? 'selected': ''}}>25</option>
                            <option value="50" {{($filterParameters['per_page']) == 50 ? 'selected': ''}}>50</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 d-flex">
                        <button type="submit" class="btn btn-block btn-secondary form-control me-md-2 me-0 mb-3">Filter</button>

                        <a class="btn btn-block btn-primary me-md-2 me-0 mb-3 " href="{{route('admin.departments.index')}}">Reset</a>
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
                            <th>Name</th>
                            <th>Department Head</th>
                            <th class="text-center">Total Employees</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th class="text-center">Branch Name</th>
                            <th class="text-center">Status</th>

                            @canany(['edit_department','delete_department'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @forelse($departments as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ucfirst($value->dept_name)}}</td>
                                <td>{{isset($value->departmentHead) ? $value->departmentHead->name :'N/A'}}</td>
                                <td class="text-center">{{$value->employees_count}}</td>
                                <td>{{$value->address}}</td>
                                <td>{{$value->phone}}</td>
                                <td class="text-center">{{$value->branch->name}}</td>
                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.departments.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                @canany(['edit_department','delete_department'])
                                    <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                        @can('edit_department')
                                            <li class="me-2">
                                                <a href="{{route('admin.departments.edit',$value->id)}}">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_department')
                                            <li>
                                                <a class="deleteBranch"
                                                   data-href="{{route('admin.departments.delete',$value->id)}}">
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
            {{$departments->appends($_GET)->links()}}
        </div>



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






