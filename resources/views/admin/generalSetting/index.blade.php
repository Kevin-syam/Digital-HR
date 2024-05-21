@extends('layouts.master')

@section('title','General Setting')

@section('action','General Setting Listing')


@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.generalSetting.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name </th>
                            <th>Value</th>
                            @can('general_setting_update')
                                <th>Action</th>
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            @forelse($generalSettings as $key => $datum)
                                <form class="forms-sample" action="{{route('admin.general-settings.update',$datum->id)}}" method="post">
                        @method('PUT')
                        @csrf
                        <tr>
                            <td>
                                <i class="link-icon" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{$datum->description}}" data-feather="info"></i>
                            </td>
                            <td>{{ucfirst($datum->name)}} <span style="color: red">*</span></td>
                            <td>
                                <input type="text" class="form-control" id="value" name="value" value="{{ $datum->value}}" autocomplete="off">
                            </td>

                            @can('general_setting_update')
                                <td>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="link-icon" data-feather="plus"></i> update
                                    </button>
                                </td>
                            @endcan
                        </tr>
                        </form>
                        @empty
                            <tr>
                                <td colspan="100%">
                                    <p class="text-center"><b>No records found!</b></p>
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection






