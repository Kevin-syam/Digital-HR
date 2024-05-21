
@extends('layouts.master')

@section('title','Edit Post')

{{--@section('nav-head','Company')--}}

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.posts.index')}}">Department section</a></li>
                <li class="breadcrumb-item active" aria-current="page">Department Edit</li>
            </ol>
            <a href="{{route('admin.posts.index')}}" >
                <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
            </a>
        </nav>
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{route('admin.posts.update',$postDetail->id)}}" enctype="multipart/form-data" method="post">
                    @method('PUT')
                    @csrf
                    @include('admin.post.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection

