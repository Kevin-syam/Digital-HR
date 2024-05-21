
@extends('layouts.master')

@section('title','Show User Details')

@section('action','Show Users Detail')

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.users.common.breadComb')

        <div class="row">

                <div class="col-md-4">
                    <strong>Name</strong> : <p>{{ucfirst($userDetail->name)}}</p><br>
                    <strong>UserName</strong> : <p>{{($userDetail->username)}}</p><br>
                    <strong>Email</strong> : <p>{{($userDetail->email)}}</p><br>
                    <strong>Gender</strong> : <p>{{($userDetail->gender)}}</p><br>
                    <strong>Address</strong> : <p>{{ucfirst($userDetail->address)}}</p><br>
                    <strong>Phone</strong> : <p>{{($userDetail->phone)}}</p><br>
                    <strong>Role</strong> : <p>{{ucfirst($userDetail->role->name)}}</p><br>
                    <strong>Is Active</strong> : <p>{{($userDetail->is_active == 1) ? 'Yes':'No'}}</p><br>
                    <strong>Date of Birth</strong> : <p>{{($userDetail->dob) }}</p><br>
                </div>

                <div class="col-md-4">
                    <img  src="{{asset(\App\Models\User::AVATAR_UPLOAD_PATH.$userDetail->avatar)}}"
                          alt="" width="250"
                          height="250">
                </div>


                <div class="col-md-4">
                    <strong>Branch</strong> : <p>{{ucfirst($userDetail->branch->name)}}</p><br>
                    <strong>Department</strong> : <p>{{($userDetail->department->dept_name)}}</p><br>
                    <strong>Post</strong> : <p>{{ $userDetail->post ? ucfirst($userDetail->post->post_name) : 'N/A'}}</p><br>
                    <strong>Employment Type</strong> : <p>{{ucfirst($userDetail->employment_type)}}</p><br>
                    <strong>Joining Date</strong> : <p>{{($userDetail->joining_date) }}</p><br>
                    <strong>User Type</strong> : <p>{{ucfirst($userDetail->user_type)}}</p><br>
                    <strong>WorkSpace</strong> : <p>{{($userDetail->workspace==1) ? 'Office' : 'Home'}}</p><br>
                    <strong>Bank Name</strong> : <p>{{ucfirst($userDetail->bank_name)}}</p><br>
                    <strong>Bank Account Number</strong> : <p>{{($userDetail->bank_account_no)}}</p><br>
                    <strong>Bank Account Type</strong> : <p>{{ucfirst($userDetail->bank_account_type) }}</p><br>

                </div>



        </div>



    </section>
@endsection


