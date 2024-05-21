
@extends('layouts.master')

@section('title','Company')

{{--@section('nav-head','Company')--}}

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Company Profile</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <h4 class="mb-4">Company Profile</h4>
                @if(!$companyDetail)
                    <form class="forms-sample" action="{{route('admin.company.store')}}" enctype="multipart/form-data" method="POST">
                        @else
                            <form class="forms-sample" action="{{route('admin.company.update',$companyDetail->id)}}" enctype="multipart/form-data" method="post">
                                @method('PUT')
                                @endif
                                @csrf
                                @include('admin.company.form')
                    </form>
            </div>
        </div>

    </section>
@endsection

