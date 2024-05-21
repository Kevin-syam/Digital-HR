
@extends('layouts.master')

@section('title','Holiday')

@section('action','CSV Import')

@section('button')
    <div class="float-end">
        <a href="{{route('admin.holidays.index')}}" >
            <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
        </a>
    </div>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.holiday.common.breadcrumb')
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="row">
                        <div class="card-body  col-md-6">

                            <h4 class="mb-4">Holiday Detail CSV</h4>
                            <form class="forms-sample" action="{{ route('admin.holidays.import-csv.store')}}" enctype="multipart/form-data" method="POST">
                                @csrf

                                <input type="file" name="file" class="form-control">
                                <br>
                                <button class="btn btn-success">Import </button>

                            </form>
                        </div>

                        <div class="card-body mt-2 col-md-6">
                            <h4 class="mb-4">Holiday CSV Example</h4>
                            <div class="col-md-12">
                                <img  src="{{asset('assets/images/sample-csv-holiday.png')}}"
                                      alt="" width="100%"
                                      >

                            </div>
                        </div>
                    </div>



                </div>




            </div>
        </div>

    </section>
@endsection
