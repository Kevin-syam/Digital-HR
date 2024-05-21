@extends('layouts.master')

@section('title',$formData['title'])

@section('action',$formData['title'])

@section('button')
    <div class="float-end">
        <a href="{{route('admin.projects.show',$projectId)}}" >
            <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
        </a>
    </div>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.project.common.breadcrumb')

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" id="addEmployeeToProjectForm" action="{{$formData['url']}}"  method="post" >
                           @csrf
                            <div class="row align-items-center justify-content-between ">
                                <div class="col-lg-8 ">
                                    <input type="hidden" value="{{$projectId}}" name="project_id" />
                                    <label for="employee" class="form-label">{{ $formData['label']}} <span style="color: red">*</span></label>
                                    <br>
                                    <select class="w-100 from-select" id="employeeAdd" name="employee[]" multiple="multiple" required>
                                        @foreach($employees as $key => $value)
                                            <option value="{{$value->id}}" {{ in_array($value->id,$alreadyAssignedEmployee)  ? 'selected' : '' }} >
                                                {{ucfirst($value->name)}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mt-4">

                                    <button type="submit" class="btn btn-primary  submit float-end"><i class="link-icon" data-feather="plus"></i>update</button>
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

    @include('admin.project.common.scripts')


@endsection







