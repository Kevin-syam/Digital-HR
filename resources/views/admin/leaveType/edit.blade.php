
@extends('layouts.master')

@section('title','Leave Type')

@section('action','Edit')

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.leaveType.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{route('admin.leaves.update',$leaveDetail->id)}}"  method="post">
                    @method('PUT')
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

