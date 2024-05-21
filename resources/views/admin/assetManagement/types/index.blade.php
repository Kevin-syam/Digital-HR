@extends('layouts.master')
@section('title','Asset Types')
@section('action','Lists')
@section('button')
    @can('create_type')
        <a href="{{ route('admin.asset-types.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>Add Types
            </button>
        </a>
    @endcan
@endsection

@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')
        @include('admin.assetManagement.types.common.breadcrumb')
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th class="text-center">Asset Item Count</th>
                            <th class="text-center">Status</th>
                            @canany(['edit_type','delete_type'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        @forelse($assetTypeLists as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ucfirst($value->name)}}</td>
                                <td class="text-center">
                                    <a href="{{route('admin.asset-types.show',$value->id)}}"> {{$value->assets_count}}</a>
                                </td>
                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.asset-types.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                        @can('edit_type')
                                            <li class="me-2">
                                                <a href="{{route('admin.asset-types.edit',$value->id)}}" title="Edit Detail">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_type')
                                            <li>
                                                <a class="delete"
                                                   data-href="{{route('admin.asset-types.delete',$value->id)}}" title="Delete">
                                                    <i class="link-icon"  data-feather="delete"></i>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </td>

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

@section('scripts')
    @include('admin.assetManagement.types.common.scripts')
@endsection






