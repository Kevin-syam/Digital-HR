@extends('layouts.master')

@section('title','Show Asset Detail')

@section('action','Show Detail')

@section('button')
    <div class="float-end">
        <a href="{{route('admin.assets.index')}}" >
            <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
        </a>
    </div>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.assetManagement.assetDetail.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="name" class="form-label">Name </label>
                        <input type="text" class="form-control"
                               id="name"
                               value="{{$assetDetail->name}}"
                               readonly >
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="type" class="form-label">Type</label>
                        <input type="text"
                               class="form-control"
                               id="name"
                               value="{{$assetDetail->type->name}}"
                               readonly >
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="assetCode" class="form-label">Asset Code </label>
                        <input type="text" class="form-control"
                               id="assetCode"
                               value="{{$assetDetail?->asset_code}}"
                              readonly >
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="assetCode" class="form-label">Asset Serial Number</label>
                        <input type="text" class="form-control"
                               id="assetCode"
                               value="{{$assetDetail?->asset_serial_no}}"
                               readonly >
                    </div>


                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="is_working" class="form-label">Is Working</label>
                        <input type="text"
                               class="form-control"
                               value="{{ucfirst($assetDetail->is_working)}}"
                               readonly >
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="purchased_date" class="form-label">Purchased Date</label>
                        <input type="date"
                               class="form-control"
                               value="{{ ($assetDetail->purchased_date)}}"
                               readonly >
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="warranty_available" class="form-label">Warranty Available </label>
                        <input type="text"
                               class="form-control"
                               id="warranty_available"
                               value="{{$assetDetail->is_available ? 'Yes' : 'No'}}"
                               readonly >
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="warranty_end_date" class="form-label">Warranty End Date</label>
                        <input type="text"
                               class="form-control"
                               id="warranty_end_date"
                               value="{{($assetDetail->warranty_end_date )}}"
                              readonly >
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="is_available" class="form-label">Available For Employee </label>
                        <input type="text"
                               class="form-control"
                               id="is_available"
                               value="{{$assetDetail->is_available ? 'Yes' : 'No'}}"
                               readonly >
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="assigned_to" class="form-label">Asset Assigned Employee  </label>
                        <input type="text"
                               class="form-control"
                               id="assignTo"
                               value="{{ucfirst($assetDetail?->assignedTo?->name)}}"
                               readonly >
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="assigned_date" class="form-label">Assigned Date</label>
                        <input type="text"
                               class="form-control"
                               id="assigned_date"
                               value="{{ ($assetDetail?->assigned_date)}}"
                               readonly >

                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="note" class="form-label">Description</label>
                        <div class="rounded p-3"  style="background-color: #e9ecef">
                            {!!  $assetDetail->note !!}
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="image" class="form-label d-block ">Asset Image </label>
                        <img id="image-preview" class="rounded p-4"
                             src="{{  asset(\App\Models\Asset::UPLOAD_PATH.$assetDetail->image)}}"
                             style="object-fit: contain; background-color: #e9ecef"
                        >
                    </div>


                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('admin.assetManagement.assetDetail.common.scripts')
@endsection

