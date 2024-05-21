@extends('layouts.master')

@section('title','Assets Lists')

@section('action','Lists')

@section('button')
    @can('create_assets')
        <a href="{{ route('admin.assets.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>Add Asset
            </button>
        </a>
    @endcan
@endsection

@section('main-content')
    <section class="content">
        @include('admin.section.flash_message')

        @include('admin.assetManagement.assetDetail.common.breadcrumb')

        <div class="search-box p-4  bg-white rounded mb-3 box-shadow pb-2">

            <form class="forms-sample" action="{{route('admin.assets.index')}}" method="get">

                <h5 class="mb-3">Assets Filter</h5>

                <div class="row align-items-center">
                    <div class="col-xxl col-xl-4 col-md-6 mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" placeholder="Asset name" id="asset" name="name" value="{{$filterParameters['name']}}" class="form-control">
                    </div>

                    <div class="col-xxl col-xl-4  col-md-6 mb-3">
                        <label for="" class="form-label">Type</label>
                        <select class="form-select form-select-lg" name="type" id="type">
                            <option value="" {{!isset($filterParameters['type']) ? 'selected': ''}} > All </option>
                            @foreach($assetType as $key => $value)
                                <option value="{{$value->id}}" {{ isset($filterParameters['type']) && $filterParameters['type'] == $value->id ? 'selected': '' }}>
                                    {{ucfirst($value->name)}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-4 col-md-6 mb-3">
                        <label for="" class="form-label">Working Status</label>
                        <select class="form-select form-select-lg" name="is_working" id="is_working">
                            <option value="" {{!isset($filterParameters['is_working']) ? 'selected': ''}} > All </option>
                            @foreach(\App\Models\Asset::IS_WORKING as $value)
                                <option value="{{$value}}" {{ isset($filterParameters['is_working']) && $filterParameters['is_working'] == $value ? 'selected': '' }}>
                                    {{ucfirst($value)}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-4 col-md-6 mb-3">
                        <label for="" class="form-label">Availability Status</label>
                        <select class="form-select form-select-lg" name="is_available" id="is_available">
                            <option value="" {{!isset($filterParameters['is_available']) ? 'selected': ''}} >All</option>
                            <option value="1" {{isset($filterParameters['is_available']) && $filterParameters['is_available'] == 1 ? 'selected': ''}} >Yes Available</option>
                            <option value="0" {{isset($filterParameters['is_available']) && $filterParameters['is_available'] == 0 ? 'selected': ''}} >Not Available</option>
                        </select>
                    </div>

                    @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                        <div class="col-xxl col-xl-4 col-md-6 mb-3">
                            <label for="" class="form-label">Purchased From</label>
                            <input type="text"  id="nepali-datepicker-from"
                                   name="purchased_from"
                                   value="{{$filterParameters['purchased_from']}}"
                                   placeholder="mm/dd/yyyy"
                                   class="form-control purchasedFrom"/>
                        </div>

                        <div class="col-xxl col-xl-4 col-md-6 mb-3">
                            <label for="" class="form-label">Purchased To </label>
                            <input type="text" id="nepali-datepicker-to"
                                   name="purchased_to"
                                   value="{{$filterParameters['purchased_to']}}"
                                   placeholder="mm/dd/yyyy"
                                   class="form-control purchasedTo"/>
                        </div>
                    @else
                        <div class="col-xxl col-xl-4 col-md-6 mb-3">
                            <label for="" class="form-label">Purchased From</label>
                            <input type="date"  value="{{$filterParameters['purchased_from']}}" name="purchased_from" class="form-control">
                        </div>

                        <div class="col-xxl col-xl-4 col-md-6 mb-3">
                            <label for="" class="form-label">Purchased To</label>
                            <input type="date"  value="{{$filterParameters['purchased_to']}}" name="purchased_to" class="form-control">
                        </div>
                    @endif

                    <div class="col-lg-12 mb-3">
                        <div class="d-flex float-end">
                            <button type="submit" class="btn btn-block btn-secondary me-2">Filter</button>
                            <a href="{{route('admin.assets.index')}}" class="btn btn-block btn-primary">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card support-main">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th class="text-center">Purchased Date</th>
                            <th class="text-center">Is Working</th>
                            <th class="text-center">Is Available</th>
                            @canany(['show_asset','edit_assets','delete_assets'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($assetLists as $key => $value)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{ucfirst($value->name)}}</td>
                                    <td><a href="{{route('admin.asset-types.show',$value->type_id)}}">{{ucfirst($value->type->name)}}</a></td>

                                    <td class="text-center">
                                        {{\App\Helpers\AppHelper::formatDateForView($value->purchased_date)}}
                                    </td>

                                    <td class="text-center">{{ucfirst($value->is_working)}}</td>

                                    <td class="text-center">
                                        <label class="switch">
                                            <input class="toggleStatus" href="{{route('admin.assets.change-Availability-status',$value->id)}}"
                                                   type="checkbox" {{($value->is_available) == 1 ?'checked':''}}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>

                                    <td class="text-center">
                                        <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                            @can('edit_assets')
                                                <li class="me-2">
                                                    <a href="{{route('admin.assets.edit',$value->id)}}" title="Edit">
                                                        <i class="link-icon" data-feather="edit"></i>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('show_asset')
                                                <li class="me-2">
                                                    <a href="{{route('admin.assets.show',$value->id)}}" title="Show Detail">
                                                        <i class="link-icon" data-feather="eye"></i>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('delete_assets')
                                                <li>
                                                    <a class="delete"
                                                       data-title="{{$value->name}} Asset Detail"
                                                       data-href="{{route('admin.assets.delete',$value->id)}}"
                                                       title="Delete">
                                                        <i class="link-icon"  data-feather="delete"></i>
                                                    </a>
                                                </li>
                                            @endcan
                                          </ul>
                                    </td>
                                </tr>
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

        <div class="dataTables_paginate mt-3">
            {{$assetLists->appends($_GET)->links()}}
        </div>
    </section>

@endsection

@section('scripts')
    @include('admin.assetManagement.assetDetail.common.scripts')
@endsection

