@extends('layouts.master')

@section('title','Router')

@section('action','Router Lists ')

@section('button')
    @can('create_router')
        <a href="{{ route('admin.routers.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>Add Router
            </button>
        </a>
    @endcan
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.router.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Router SSID</th>
                            <th>Branch </th>
                            <th>Company</th>
                            <th>Status</th>
                            @canany(['edit_router','delete_router'])
                                <th>Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @forelse($routers as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{($value->router_ssid)}}</td>
                                <td>{{ucfirst($value->branch->name)}}</td>
                                <td>{{ucfirst($value->company->name)}}</td>
                                <td>
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.routers.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                @canany(['edit_router','delete_router'])
                                    <td>
                                    <ul class="d-flex list-unstyled mb-0">
                                        @can('edit_router')
                                            <li class="me-2">
                                                <a href="{{route('admin.routers.edit',$value->id)}}" title="Edit">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_router')
                                            <li>
                                                <a class="deleteRouter"
                                                   data-href="{{route('admin.routers.delete',$value->id)}}" title="Delete">
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

        <div class="dataTables_paginate">
            {{$routers->appends($_GET)->links()}}
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
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }else if (result.isDenied) {
                        (status === 0)? $(this).prop('checked', true) :  $(this).prop('checked', false)
                    }
                })
            })

            $('.deleteRouter').click(function (event) {
                event.preventDefault();
                let href = $(this).data('href');
                Swal.fire({
                    title: 'Are you sure you want to Delete Router Detail ?',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    padding:'10px 50px 10px 50px',
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

