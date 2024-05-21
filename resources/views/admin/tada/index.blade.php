@extends('layouts.master')
@section('title','Tadas')
@section('action','Tada Listing')

@section('button')
    @can('create_tada')
        <a href="{{ route('admin.tadas.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>Create Tada
            </button>
        </a>
    @endcan
@endsection

@section('main-content')
    <section class="content">
        @include('admin.section.flash_message')

        <div id="showFlashMessageResponse">
            <div class="alert alert-danger error d-none">
                <p class="errorMessageDelete"></p>
            </div>
        </div>

        @include('admin.tada.common.breadcrumb')

        <div class="search-box p-4 bg-white rounded mb-3 box-shadow pb-2">
            <form class="forms-sample" action="{{route('admin.tadas.index')}}" method="get">
                <div class="row align-items-center">
                    <div class="col-lg-3 mb-3">
                        <h5>Tada Filter</h5>
                    </div>

                    <div class="col-lg-3 col-md-4 mb-3">
                        <input type="text" name="employee" id="employee" placeholder="search by employee name" value="{{$filterParameters['employee']}}" class="form-control" />
                    </div>

                    <div class="col-lg-3 col-md-4 mb-3">
                        <select class="form-select" id="status" name="status" >
                            <option value="">Search by Status</option>
                            @foreach(\App\Models\Tada::STATUS as $value)
                                <option value="{{$value}}" {{ isset($filterParameters['status']) && $filterParameters['status'] == $value ? 'selected':''}}> {{ucfirst($value)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-4 mb-3">
                        <div class="d-flex float-end">
                            <button type="submit" class="btn btn-block btn-secondary me-2">Filter</button>
                            <a class="btn btn-block btn-danger" href="{{route('admin.tadas.index')}}">Reset</a>
{{--                            <button type="button" class="btn btn-block btn-danger reset">Reset</button>--}}
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
                            <th>Employee</th>
                            <th>Title</th>
                            <th class="text-center">Expense(Rs.)</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Is Active</th>
                            <th>Submitted Date</th>
                            @canany(['show_tada_detail','edit_tada','delete_tada'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <?php
                                $status = [
                                    'pending' => 'info',
                                    'accepted' => 'success',
                                    'rejected' => 'danger',
                                ];
                            ?>
                        @forelse($tadaLists as $key => $value)
                            <tr>
                                <td>
                                    {{++$key}}
                                </td>
                                <td>{{($value->employeeDetail->name)}}</td>
                                <td>{{ ucfirst(\Illuminate\Support\Str::limit($value->title, 30, $end='...')) }}</td>
                                <td class="text-center">{{number_format($value->total_expense)}}</td>
                                <td class="text-center">
                                    <span
                                        class="btn btn-{{$status[$value->status]}} btn-xs"
                                        id="updateStatus"
                                        data-id="{{ $value->id }}"
                                        data-status="{{($value->status)}}"
                                        data-title="{{ucfirst($value->title)}}"
                                        data-reason="{{($value->remark)}}"
                                        data-action="{{route('admin.tadas.update-status',$value->id)}}">{{ucfirst($value->status)}}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.tadas.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>{{ \App\Helpers\AppHelper::formatDateForView($value->created_at)}}</td>

                                @canany(['show_tada_detail','edit_tada','delete_tada'])
                                <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                        @can('edit_tada')
                                            <li class="me-2">
                                                <a href="{{route('admin.tadas.edit',$value->id)}}" title="Edit">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('show_tada_detail')
                                            <li class="me-2">
                                                <a href="{{route('admin.tadas.show',$value->id)}}"
                                                   id="show"
                                                   title="show detail"
                                                   data-id="{{ $value->id }}">
                                                    <i class="link-icon" data-feather="eye"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_tada')
                                            <li>
                                                <a class="delete"
                                                   data-href="{{route('admin.tadas.delete',$value->id)}}"
                                                   data-title="Tada Detail"
                                                   title="Delete">
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
            {{$tadaLists->appends($_GET)->links()}}
        </div>
    </section>
    @include('admin.tada.update_status_form')
@endsection

@section('scripts')
    @include('admin.tada.common.scripts')
@endsection






