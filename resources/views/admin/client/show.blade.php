@extends('layouts.master')

@section('title','Client Invoices')

@section('action','Client Invoices')

@section('button')
    <a href="{{route('admin.clients.index')}}" >
        <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
    </a>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.client.common.breadcrumb')

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5>Client Detail: </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-2 mb-4">
                                        <h6>Client Avatar</h6>
                                        <div class="uploaded-image">
                                            <a href="#">
                                                <img class="w-100" style=""
                                                     src="{{asset(\App\Models\Client::UPLOAD_PATH.$clientDetail->avatar)}}"
                                                     alt="avatar">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <h6 class="mb-1">Name</h6>
                                        <p> {{($clientDetail->name)}}</p>
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <h6 class="mb-1">Phone</h6>
                                        <p> {{($clientDetail->contact_no)}}</p>
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <h6 class="mb-1">Email</h6>
                                        <p> {{($clientDetail->email)}}</p>
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <h6 class="mb-1">Address</h6>
                                        <p> {{($clientDetail->address ?? 'N/A')}}</p>
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <h6 class="mb-1">Country</h6>
                                        <p> {{($clientDetail->country ?? 'N/A')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5>Client Project Lists: </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project</th>
                                    <th>Date Start</th>
                                    <th>Deadline</th>
                                    <th>Amount(Rs.)</th>
                                    <th>status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                <?php
                                    $ProjectStatus = [
                                        'in_progress' => 'primary',
                                        'not_started' => 'primary',
                                        'on_hold' => 'info',
                                        'cancelled' => 'danger',
                                        'completed' => 'success',
                                    ]
                                ?>
                                @forelse($clientDetail->projects as $key => $value)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>
                                            <a href="{{route('admin.projects.show',$value->id)}}">{{ucfirst($value->name)}}</a><br>
                                            <small>All Tasks :{{($value->tasks->count())}}</small><br>
                                            <small>Completed Tasks :{{($value->completedTask->count())}}</small>
                                        </td>
                                        <td>{{  \App\Helpers\AppHelper::formatDateForView($value->start_date)}}</td>
                                        <td>{{  \App\Helpers\AppHelper::formatDateForView($value->deadline)}}</td>
                                        <td>{{number_format($value->cost)}}</td>
                                        <td>
                                            <span class="btn btn-{{$ProjectStatus[$value->status]}} btn-xs"> {{\App\Helpers\PMHelper::STATUS[$value->status]}}</span>
                                        </td>
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

    </section>
@endsection

@section('scripts')
    @include('admin.client.common.scripts')
@endsection
