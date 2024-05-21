
@extends('layouts.master')

@section('title','Edit Task Checklist ')

@section('action','Edit Task Checklist')

@section('button')
    <div class="float-end">
        <a href="{{route('admin.tasks.show',$checklistDetail->task->id)}}" >
            <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
        </a>
    </div>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.task.common.breadcrumb')

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="checklistEdit" class="forms-sample" action="{{route('admin.task-checklists.update',$checklistDetail->id)}}"  method="post">
                            @method('PUT')
                            @csrf
                            <div class="row">

                                <div class="col-lg-6 mb-3">
                                    <label for="task_id" class="form-label">Task Name <span style="color: red">*</span> </label>
                                    <select class="form-select" id="task_id" name="task_id" readonly="true"  required >
                                        <option value="{{$checklistDetail->task->id}}"  selected >{{$checklistDetail->task->name}}</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="name" class="form-label"> Checklist Name <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required value="{{ ( isset( $checklistDetail) ?  $checklistDetail->name: '' )}}"
                                           autocomplete="off" >
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="assigned_to" class="form-label">Assign Task checklist <span style="color: red">*</span> </label>
                                    <select class="form-select" id="assigned_to" name="assigned_to" required >
                                        @foreach($checklistDetail->task->assignedMembers as $key => $value)
                                            <option value="{{$value->user->id}}" {{  ($checklistDetail->assigned_to == $value->user->id) ? 'selected': '' }}>
                                                {{$value->user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="text-center mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="link-icon" data-feather="edit-2"></i> Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    @include('admin.task.common.scripts')
@endsection

