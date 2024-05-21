@extends('layouts.master')

@section('title','Logout Requests')


@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Logout Requests</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Name</th>
                            <th>Logout Request Status</th>
                        </tr>
                        </thead>
                        <tr>
                            <tbody>
                            @forelse($logoutRequests as $key => $value)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td><strong>{{removeSpecialChars($value->name)}}</strong></td>
                                    <td>
                                        <button class="btn btn-primary btn-xs acceptLogoutRequest"
                                                href="{{route('admin.logout-requests.accept',$value->id)}}">
                                            Take action
                                        </button>
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

    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.acceptLogoutRequest').click(function (event) {
                event.preventDefault();
                let href = $(this).attr('href');
                Swal.fire({
                    title: 'Are you sure you want to Accept Logout Request ?',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    padding: '10px 50px 10px 50px',
                    // width:'500px',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                })
            })
        });

    </script>
@endsection




