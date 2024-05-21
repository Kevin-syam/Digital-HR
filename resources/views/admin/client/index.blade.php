
@extends('layouts.master')

@section('title','Clients ')

@section('action','Client Listing')

@section('button')
    @can('create_client')
        <a href="{{ route('admin.clients.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>Add Client
            </button>
        </a>
    @endcan
@endsection


@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.client.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Client Email</th>
                            <th>Contact</th>
                            <th class="text-center">Status</th>
                            @canany(['show_client_detail','edit_client','delete_client'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @forelse($clientLists as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ucfirst($value->name)}}</td>
                                <td>{{($value->email)}}</td>
                                <td>{{($value->contact_no)}}</td>

                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.clients.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                @canany(['show_client_detail','edit_client','delete_client'])
                                    <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                        @can('edit_client')
                                            <li class="me-2">
                                                <a href="{{route('admin.clients.edit',$value->id)}}" title="Edit Client Detail">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('show_client_detail')
                                            <li class="me-2">
                                                <a href="{{route('admin.clients.show',$value->id)}}" title="show Client Detail">
                                                    <i class="link-icon" data-feather="eye"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_client')
                                            <li>
                                                <a class="deleteClientDetail"
                                                   data-href="{{route('admin.clients.delete',$value->id)}}" title="Delete Client Detail">
                                                    <i class="link-icon"  data-feather="delete"></i>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </td>
                                @endcanany
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
    @include('admin.client.common.scripts')
@endsection






