
@extends('layouts.master')

@section('title','Create Client')

@section('action','Create')

@section('button')
    <a href="{{route('admin.clients.index')}}" >
        <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
    </a>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.client.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form id="clientAdd" class="forms-sample" action="{{route('admin.clients.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @include('admin.client.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection

@section('scripts')

    @include('admin.client.common.scripts')

@endsection
