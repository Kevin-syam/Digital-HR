@extends('layouts.master')

@section('title','Assign Permission Setting')

@section('action','Role Permission Detail')

@section('button')
    <a href="{{route('admin.roles.index')}}" class="btn btn-primary btn-sm"> <i class="link-icon" data-feather="arrow-left"></i> Back </a>
@endsection

@section('main-content')
    <section class="content">
        @include('admin.section.flash_message')

        @include('admin.role.common.breadcrumb')

        <div class="card">
            <div class="card-header card-nav">
                <ul class="nav nav-tabs ">
                    @foreach($allRoles as $key => $value)
                        <a class="nav-link {{$value->id == $role->id ? 'active': ''}}" href="{{route('admin.roles.permission',$value->id)}}">
                            <button class="btn btn-md btn-{{$value->id == $role->id ? 'primary':'secondary'}}">{{ucfirst($value->name)}} </button>
                        </a>
                    @endforeach
                </ul>
            </div>
            <div class="card-body card-nav-content">
                <form class="forms-sample" action="{{route('admin.role.assign-permissions',$role->id)}}" method="post">
                    @method('PUT')
                    @csrf
                    @include('admin.role.common.permission')
                </form>
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

            $(function() {
                $('.js-check-all').on('click', function() {
                    let isChecked = $(this).parent().parent().parent().siblings().children('.item').children().find('.module_checkbox').prop('checked');
                    if (isChecked) {
                        $(this).parent().parent().parent().siblings().children('.item').children().find('.module_checkbox').prop('checked', false);
                    }else{
                        $(this).parent().parent().parent().siblings().children('.item').children().find('.module_checkbox').prop( "checked", true);
                    }
                });
            });

            $('#web').click(function (e){
                $('.web').slideToggle('slow');
            });

            $('#api').click(function (e){
                $('.api').slideToggle('slow');
            });
        });

    </script>
@endsection






