
@extends('layouts.master')

@section('title','Create Post')

{{--@section('nav-head','Company')--}}


@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.posts.index')}}">Post section</a></li>
                <li class="breadcrumb-item active" aria-current="page">Post create</li>
            </ol>


            <a href="{{route('admin.posts.index')}}" >
                <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
            </a>
        </nav>

        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{route('admin.posts.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @include('admin.post.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection
