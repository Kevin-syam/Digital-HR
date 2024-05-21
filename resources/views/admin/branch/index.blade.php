
@extends('layouts.master')

@section('title','Branch')

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.branch.index')}}">Branch section</a></li>
                <li class="breadcrumb-item active" aria-current="page">Branches</li>
            </ol>

            @can('create_branch')
                <button
                    class="btn btn-primary add_branch"
                    data-bs-toggle="modal"
                    data-href="{{route('admin.branch.create')}}"
                    data-bs-target="#addslider">
                    <i class="link-icon" data-feather="plus"></i> Add Branch
                </button>
            @endcan
        </nav>

        <div class="search-box p-4 bg-white rounded mb-3 box-shadow pb-2">
            <form class="forms-sample" action="{{route('admin.branch.index')}}" method="get">
                <div class="row align-items-center">

                    <div class="col-lg-2 mb-3">
                        <h5>Branch Lists</h5>
                    </div>

                    <div class="col-lg-4 col-md-4 mb-3">
                        <input type="text" placeholder="Search by Branch name" name="name" value="{{($filterParameters['name'])}}" class="form-control">
                    </div>

                    <div class="col-lg-4 col-md-4 mb-3">
                        <select class="form-select form-select-lg" name="per_page">
                            <option value="10" {{($filterParameters['per_page']) == 10 ? 'selected': ''}}>10</option>
                            <option value="25" {{($filterParameters['per_page']) == 25 ? 'selected': ''}}>25</option>
                            <option value="50" {{($filterParameters['per_page']) == 50 ? 'selected': ''}}>50</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-3 d-flex">
                        <button type="submit" class="btn btn-block btn-secondary form-control me-md-2 me-0 mb-3">Filter</button>

                        <a class="btn btn-block btn-primary me-md-2 me-0 mb-3" href="{{route('admin.branch.index')}}">Reset</a>
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
                            <th>Branch Name</th>
                            <th>Address</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Total Employee</th>
                            <th class="text-center">Status</th>
                            @canany(['edit_branch','delete_branch'])
                                <th class="text-center">Action</th>
                            @endcan
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($branches as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ucfirst($value->name)}}</td>
                                <td>{{$value->address}}</td>
                                <td class="text-center">{{$value->phone}}</td>
                                <td class="text-center">{{$value->employees_count }}</td>
                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.branch.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                @canany(['edit_branch','delete_branch'])
                                    <td class="text-center">
                                        <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                            @can('edit_branch')
                                                <li class="me-2">
                                                    <a href=""
                                                       id="editBranch"
                                                       data-href="{{route('admin.branch.edit',$value->id)}}"
                                                       data-id="{{ $value->id }}">
                                                        <i class="link-icon" data-feather="edit"></i>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('delete_branch')
                                                <li>
                                                    <a class="deleteBranch" data-href="{{route('admin.branch.delete',$value->id)}}"><i class="link-icon"  data-feather="delete"></i></a>
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

        <div class="dataTables_paginate">
            {{$branches->appends($_GET)->links()}}
        </div>

        @include('admin.branch.modal-form')

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
                    title: 'Are you sure you want to change  status ?',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    padding:'10px 50px 10px 50px',
                    // width:'1000px',
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
                    title: 'Are you sure you want to Delete Branch ?',
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

            $('body').on('click', '.add_branch', function (event) {
                event.preventDefault();
                let url = $(this).data('href');
                $('#branch_head').find('option').not(':first').remove();
                $.get(url, function (data) {
                    let len = 0;
                    if(data.users != null){
                        len = data.users.length;
                    }
                    if(len > 0) {
                        for (let i = 0; i < len; i++) {
                            let id = data.users[i].id;
                            let name = data.users[i].name;
                            let option = "<option value='" + id + "'>" + name + "</option>";
                            $("#branch_head").append(option);
                        }
                    }
                    $('.modal-title').html('Branch Create');
                    $('#name').val('');
                    $('#address').val('');
                    $('#company_id').val(data.company.id);
                    $('#phone').val('');
                    $('#status').val('');
                    $('#branch_location_latitude').val('');
                    $('#branch_location_longitude').val('');
                    $('#update').val('post');
                    $('#branch_form').attr('action', "{{route('admin.branch.store')}}");
                    $('#submit-btn').html("Create");
                    $('#addslider').modal('show');
                });
            });

            $('body').on('click', '#editBranch', function (event) {
                event.preventDefault();
                let url = $(this).data('href');
                $('#branch_head').find('option').not(':first').remove();
                $.get(url, function (data) {
                    let len = 0;
                    if(data.users != null){
                        len = data.users.length;
                    }
                    if(len > 0) {
                        for (let i = 0; i < len; i++) {
                            let id = data.users[i].id;
                            let name = data.users[i].name;
                            let option = "<option " + ((data.data.branch_head_id == id) ? "selected" : '') + " value='"+id+"'    >" + name + "</option>";
                            $("#branch_head").append(option);
                        }
                    }
                    $('.modal-title').html('Branch Update');
                    $('#name').val(data.data.name);
                    $('#address').val(data.data.address);
                    $('#company_id').val(data.data.company_id);
                    $('#branch_head').val(data.data.branch_head_id);
                    $('.mobile').val(data.data.phone);
                    $('#branch_location_latitude').val(data.data.branch_location_latitude);
                    $('#branch_location_longitude').val(data.data.branch_location_longitude);
                    $('#status').val(data.data.is_active);
                    $('#update').val('put');
                    $('#branch_form').attr('action',"/admin/branch/"+ data.data.id);
                    $('#submit-btn').html("Update ");
                    $('#addslider').modal('show');
                })
            }).trigger("change");

        });

    </script>
@endsection






