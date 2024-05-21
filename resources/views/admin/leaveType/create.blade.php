
@extends('layouts.master')

@section('title','Leave Type')

@section('action','Create')

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.leaveType.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{route('admin.leaves.store')}}"  method="POST">
                    @csrf
                    @include('admin.leaveType.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    @include('admin.leaveType.common.scripts')
@endsection
