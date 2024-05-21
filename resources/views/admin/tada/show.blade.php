@extends('layouts.master')

@section('title','Show Tada Detail')

@section('action','Tada Detail')

@section("styles")
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
@endsection

<?php
    $status = [
        'pending' => 'info',
        'accepted' => 'success',
        'rejected' => 'danger',
    ];
?>

@section('button')
    <div class="breadcrumb-button float-end">
        @can('edit_tada')
            <a href="{{route('admin.tadas.edit',$tadaDetail->id)}}" >
                <button class="btn btn-sm btn-secondary"><i class="link-icon me-1" data-feather="edit"></i>Tada Edit</button>
            </a>
        @endcan

        @can('create_attachment')
            <a href="{{route('admin.tadas.attachment.create',$tadaDetail->id)}}" >
                <button class="btn btn-sm btn-secondary"><i class="link-icon me-1" data-feather="clipboard"></i>Upload Attachment</button>
            </a>
        @endcan

        @can('edit_tada')
            <button class="btn btn-sm btn-secondary"
                    id="updateStatus"
                    data-id="{{ $tadaDetail->id }}"
                    data-status="{{($tadaDetail->status)}}"
                    data-title="{{ucfirst($tadaDetail->title)}}"
                    data-reason="{{($tadaDetail->remark)}}"
                    data-action="{{route('admin.tadas.update-status', $tadaDetail->id)}}"
            >
            <i class="link-icon me-1" data-feather="edit"></i>Update Status</button>
        @endcan

            <a href="{{route('admin.tadas.index')}}" >
                <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
            </a>
    </div>
@endsection

@section('main-content')


    <section class="content">
        @include('admin.section.flash_message')
        @include('admin.tada.common.breadcrumb')
        <div class="row position-relative">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-2">{{ucfirst($tadaDetail->title)}}</h3>
                    </div>

                    <div class="card-body">
                        {!!  $tadaDetail->description!!}

                        @if(isset($attachments) && count($attachments) > 0 )
                            <div class="mb-3 col-12 mt-3">
                                <h6 class="">Uploaded Attachment </h6>
                                <div class="row mb-4 mt-3">
                                    @foreach($attachments as $key => $data)
                                        @if(in_array(pathinfo(asset(\App\Models\TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment), PATHINFO_EXTENSION),['jpeg','png','jpg'])  )
                                            <div class="col-lg-3 mb-4">
                                                <div class="uploaded-image">
                                                    <a href="{{ asset(\App\Models\TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment) }}" data-lightbox="image-1" data-title="{{$data->attachment}}">
                                                        <img class="w-100" style=""
                                                             src="{{ asset(\App\Models\TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment) }}"
                                                             alt="document images">
                                                    </a>
                                                    <p>{{$data->attachment}}</p>
                                                    @can('delete_attachment')
                                                        <a class="documentDelete" id="delete" data-title="Image" href="{{route('admin.tadas.attachment-delete',$data->id)}}">
                                                            <i class="link-icon remove-image" data-feather="x"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </div>
                                        @else
                                            <div class="uploaded-files">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-1">
                                                        <div class="file-icon">
                                                            <i class="link-icon" data-feather="file-text"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-10">
                                                        <a target="_blank" href="{{asset(\App\Models\TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment)}}">
                                                            {{asset(\App\Models\TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment)}}
                                                        </a>
                                                    </div>

                                                    @can('delete_attachment')
                                                        <div class="col-lg-1">
                                                            <a class="delete" data-title="attachment file" data-href="{{route('admin.tadas.attachment-delete',$data->id)}}">
                                                                <i class="link-icon remove-files" data-feather="x"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4 sidebar-list position-relative">
                <div class="position-sticky top-0">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Tada Summary</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-border">
                                <tbody>
                                    <tr>
                                        <td>Total Expense:</td>
                                        <td class="text-end">
                                           {{number_format($tadaDetail->total_expense)}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Employee:</td>
                                        <td class="text-end text-success">{{ucfirst($tadaDetail->employeeDetail->name)}}</td>
                                    </tr>

                                    <tr>
                                        <td>Submitted Date:</td>
                                        <td class="text-end text-danger">{{ \App\Helpers\AppHelper::formatDateForView($tadaDetail->created_at)}}</td>
                                    </tr>

                                    <tr>
                                        <td>Verified By:</td>
                                        <td class="text-end text-success">
                                            {{$tadaDetail->verifiedBy ? ucfirst($tadaDetail->verifiedBy->name) : 'N/A'}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Verified Date:</td>
                                        <td class="text-end text-danger">{{ $tadaDetail->verifiedBy ? \App\Helpers\AppHelper::formatDateForView($tadaDetail->updated_at)  : 'N/A'}}</td>
                                    </tr>

                                    <tr class="border-0">
                                        <td>Status:</td>
                                        <td class="text-end">
                                            <span class="btn btn-{{$status[$tadaDetail->status]}} cursor-default btn-xs">
                                                {{ucfirst($tadaDetail->status)}}
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Remark</td>
                                        <td class="text-end">
                                            <span class="text-end text-muted"> {{$tadaDetail->remark ?? 'N/A'}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.tada.update_status_form')

    </section>
@endsection

@section('scripts')
    @include('admin.tada.common.scripts')
@endsection
