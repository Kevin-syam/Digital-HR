
@extends('layouts.master')

@section('title','Edit Role')

@section('action','Edit Role')

@section('button')
    <a href="{{route('admin.roles.index')}}" >
        <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
    </a>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.role.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{route('admin.roles.update',$roleDetail->id)}}" enctype="multipart/form-data" method="post">
                    @method('PUT')
                    @csrf
                    @include('admin.role.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection

