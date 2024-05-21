
@extends('layouts.master')

@section('title','Edit Asset Type')

@section('action','Edit Asset Type')

@section('button')
    <a href="{{route('admin.asset-types.index')}}" >
        <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
    </a>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.assetManagement.types.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form id="" class="forms-sample" action="{{route('admin.asset-types.update',$assetTypeDetail->id)}}"  method="post">
                    @method('PUT')
                    @csrf
                    @include('admin.assetManagement.types.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    @include('admin.assetManagement.types.common.scripts')
@endsection

