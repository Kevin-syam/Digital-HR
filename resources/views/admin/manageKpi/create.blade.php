
@extends('layouts.master')

@section('title','Create KPI')

@section('button')
    <a href="{{route('admin.manageKpi.index')}}" >
        <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
    </a>
@endsection

@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')
        @include('admin.manageKpi.common.breadcrumb')
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{route('admin.manageKpi.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @include('admin.manageKpi.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection

