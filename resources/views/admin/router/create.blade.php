
@extends('layouts.master')

@section('title','Add Router')

@section('action','Create')

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.router.common.breadcrumb')
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4">Router Detail</h4>
                <form class="forms-sample" action="{{route('admin.routers.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @include('admin.router.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection
