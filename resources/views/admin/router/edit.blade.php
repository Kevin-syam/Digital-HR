
@extends('layouts.master')

@section('title','Edit Router')

@section('action','Edit')

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.router.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{route('admin.routers.update',$routerDetail->id)}}" enctype="multipart/form-data" method="post">
                    @method('PUT')
                    @csrf
                    @include('admin.router.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection

