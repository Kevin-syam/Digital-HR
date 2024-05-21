@extends('layouts.master')

@section('title','Show User Details')

@section('action','Detail')

@section('button')
    <div class="float-end">
        @can('edit_employee')
            <a style="float: left;" href="{{ route('admin.users.edit',$userDetail->id)}}">
                <button class="btn btn-secondary btn-sm me-2">
                    <i class="link-icon" data-feather="edit"></i>Edit Detail
                </button>
            </a>
        @endcan

        <a href="{{route('admin.users.index')}}">
            <button class="btn btn-sm btn-primary "><i class="link-icon" data-feather="arrow-left"></i> Back</button>
        </a>
    </div>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')
        @include('admin.users.common.breadcrumb')


        <div class="d-flex align-items-center mb-4">
            <img class="wd-100 ht-100 rounded-circle" style="object-fit: cover"
                 src="{{asset(\App\Models\User::AVATAR_UPLOAD_PATH.$userDetail->avatar)}}" alt="profile">
            <div class="text-start ms-3">
                <span class="fw-bold">{{ucfirst($userDetail->name)}}</span>
                <p class="">{{ucfirst($userDetail->email)}}</p>
            </div>
        </div>

        <div class="row profile-body">
            <div class="col-lg-4 mb-4 d-flex">
                <div class="card rounded w-100">
                    <div class="card-body card-profile">

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="card-title mb-0" style="align-content: center;">User Detail</h6>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Username:</label>
                            <p class="d-inline-block">{{($userDetail->username)}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Gender:</label>
                            <p class="d-inline-block">{{ucfirst($userDetail->gender)}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Address.:</label>
                            <p class="d-inline-block">{{ucfirst($userDetail->address)}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Phone No:</label>
                            <p class="d-inline-block">{{($userDetail->phone)}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">DOB:</label>
                            <p class="d-inline-block"> {{ \App\Helpers\AppHelper::formatDateForView($userDetail->dob) }}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Role:</label>
                            <p class="d-inline-block">{{$userDetail->role ? ucfirst($userDetail->role->name):'N/A'}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Is Active:</label>
                            <p class="d-inline-block">{{($userDetail->is_active == 1) ? 'Yes':'No'}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4 d-flex'">
                <div class="card rounded w-100">
                    <div class="card-body card-profile">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="card-title mb-0" style="align-content: center;">Office Detail</h6>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Branch Name:</label>
                            <p class="d-inline-block">{{($userDetail->branch ? ucfirst($userDetail->branch->name) : 'N/A')}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Department Name:</label>
                            <p class="d-inline-block">{{$userDetail->department ? ucfirst($userDetail->department->dept_name) :'N/A'}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Post Name.:</label>
                            <p class="d-inline-block">{{ $userDetail->post ? ucfirst($userDetail->post->post_name) : 'N/A'}} </p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Employment Type:</label>
                            <p class="d-inline-block">{{ucfirst($userDetail->employment_type)}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Joining Date:</label>
                            <p class="d-inline-block">{{\App\Helpers\AppHelper::formatDateForView($userDetail->joining_date)}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">User Type:</label>
                            <p class="d-inline-block">{{ucfirst($userDetail->user_type)}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Workspace:</label>
                            <p class="d-inline-block">{{ ($userDetail->workspace_type == 1) ? 'Office' : 'Home'}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4 d-flex">
                <div class="card rounded w-100">
                    <div class="card-body card-profile">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="card-title mb-0" style="align-content: center;">Account Detail</h6>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Bank Name:</label>
                            <p class="d-inline-block">{{ucfirst($userDetail?->accountDetail?->bank_name)}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Account Number:</label>
                            <p class="d-inline-block">{{($userDetail?->accountDetail?->bank_account_no)}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Account Type:</label>
                            <p class="d-inline-block">{{ucfirst($userDetail?->accountDetail?->bank_account_type)}}</p>
                        </div>

                        <div class="mt-3 p-2">
                            <label class="fw-bolder mb-0 text-uppercase w-45">Salary:</label>
                            <p class="d-inline-block">{{ucfirst($userDetail?->accountDetail?->salary)}}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </section>
@endsection


