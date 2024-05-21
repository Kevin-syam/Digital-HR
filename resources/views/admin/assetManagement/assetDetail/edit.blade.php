@extends('layouts.master')
@section('title','Edit Asset Detail')
@section('action','Edit')
@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')
        @include('admin.assetManagement.assetDetail.common.breadcrumb')
        <div class="card">
            <div class="card-body">
                <form id="" class="forms-sample" action="{{route('admin.assets.update',$assetDetail->id)}}" enctype="multipart/form-data"  method="post">
                    @method('PUT')
                    @csrf
                    @include('admin.assetManagement.assetDetail.common.form')
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('admin.assetManagement.assetDetail.common.scripts')
@endsection

