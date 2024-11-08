@extends('layouts.master')
@section('title','scoreKpi')

@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')

        <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.scoreKpi.index')}}">Score Kpi section</a></li>
                <li class="breadcrumb-item active" aria-current="page">Scores KPI</li>
            </ol>

            @can('create_scoreKpi ')
                <a href="{{ route('admin.scoreKpi.create')}}">
                    <button class="btn btn-primary add_department">
                        <i class="link-icon" data-feather="plus"></i>Add KPI Score
                    </button>
                </a>
            @endcan
        </nav>

        <div class="search-box p-4 bg-white rounded mb-3 box-shadow pb-2">
            <form class="forms-sample" action="{{route('admin.scoreKpi.index')}}" method="get">
                <div class="row align-items-center">

                    <div class="col-lg-2 mb-3">
                        <h5>Score Kpi Lists</h5>
                    </div>

                    <div class="col-lg-4 col-md-4 mb-3">
                        <select class="form-select form-select-lg" name="department">
                            <option value="" {{!isset($filterParameters['department']) ? 'selected': ''}}>Search by department</option>
                            @foreach($departments as $key => $value)
                                <option value="{{ $key }}" {{ (isset($filterParameters['department']) && $key == $filterParameters['department'] ) ?'selected':'' }} >
                                    {{ucfirst($value)}} </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="col-lg-4 col-md-4 mb-3">
                        <input type="text" placeholder="Search by Departement name" name="name" value="{{$filterParameters['name']}}" class="form-control">
                    </div> --}}

                    <div class="col-lg-2 col-md-4 d-flex">
                        <button type="submit" class="btn btn-block btn-secondary form-control me-md-2 me-0 mb-3">Filter</button>

                        <a class="btn btn-block btn-primary me-md-2 me-0 mb-3 " href="{{route('admin.scoreKpi.index')}}">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            {{-- <th>Key Perfomance Indicator </th> --}}
                            <th class="text-center">Score</th>
                            <th class="text-center">Period</th>
                            <th>Details</th>

                            @canany(['edit_post','delete_post'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @forelse($score as $key => $value)
                            @php
                                $periodFormatted = \Carbon\Carbon::parse($value->period)->format('Y-m');
                                $collapseId = 'details-' . $value->dept_id . '-' . $periodFormatted;
                            @endphp
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ucfirst($value->department->dept_name)}}</td>
                                {{-- <td>{{ucfirst($value->kpi->kpi_desc)}}</td> --}}
                                <td>{{ucfirst($value->total_score)}}</td>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $value->period)->format('m-Y') }}</td>
                                <td>
                                    <button class="btn btn-primary" type="button" onclick="toggleDetails('{{ $collapseId }}')">
                                        View Details
                                    </button>
                                </td>

                                @canany(['edit_score','delete_scores'])
                                    <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">

                                        @can('edit_post')
                                            <li class="me-2">
                                                {{-- <a href="{{route('admin.posts.edit',$value->departement->dept_id)}}"> --}}
                                                <a href="">    
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_scores')
                                            <li>
                                                <a class="deletePost"
                                                   data-href="{{route('admin.scoreKpi.delete',$value->dept_id,$value->period)}}">
                                                   {{-- data-href="{{route('admin.scoreKpi.delete',$value->dept_id,$value->period)}}"> --}}
                                                    
                                                   <i class="link-icon"  data-feather="delete"></i>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </td>
                                @endcanany
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <div class="collapse" id="{{ $collapseId }}" style="display: none;">
                                        <div class="card card-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>KPI Description</th>
                                                        <th>Realisation</th>
                                                        <th>Weight</th>
                                                        <th>KPI Target</th>
                                                        <th>Score</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(isset($detailedScores[$value->dept_id . '-' . $value->period]))
                                                        @foreach($detailedScores[$value->dept_id . '-' . $value->period] as $detail)
                                                            <tr>
                                                                <td>{{ $detail->kpi->kpi_desc }}</td>
                                                                <td>{{ $detail->realisation }} {{ $detail->kpi->unit }}</td>
                                                                <td>{{ $detail->kpi->weight }}</td>
                                                                <td>{{ $detail->kpi->kpi_target }} {{ $detail->kpi->unit }}</td>
                                                                <td>{{ $detail->score }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="4">No details available.</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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

        <div class="dataTables_paginate">
            {{$score->appends($_GET)->links()}}
        </div>

        @include('admin.post.show')

    </section>
@endsection

@section('scripts')
   @include('admin.scoreKpi.common.scripts')
@endsection






