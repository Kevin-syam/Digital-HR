
@canany(['view_company','list_branch','list_department','list_kpi','list_scoreKpi','list_post','list_department'])
    <li class="nav-item  {{
                           request()->routeIs('admin.company.*') ||
                           request()->routeIs('admin.branch.*') ||
                           request()->routeIs('admin.departments.*') ||
                           request()->routeIs('admin.manageKpi.*') ||
                           request()->routeIs('admin.scoreKpi.*') ||
                           request()->routeIs('admin.posts.*')
                        ? 'active' : ''
                        }}   ">
        <a class="nav-link" data-bs-toggle="collapse"
           href="#company_management"
           data-href="#"
           role="button" aria-expanded="false" aria-controls="company">
            <i class="link-icon" data-feather="align-justify"></i>
            <span class="link-title">Company Management</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="{{
                           request()->routeIs('admin.company.*') ||
                           request()->routeIs('admin.branch.*') ||
                           request()->routeIs('admin.departments.*') ||
                           request()->routeIs('admin.manageKpi.*') ||
                           request()->routeIs('admin.scoreKpi.*') ||
                           request()->routeIs('admin.posts.*')   ?'' : 'collapse'  }} "  id="company_management">
            <ul class="nav sub-menu">
                @can('view_company')
                    <li class="nav-item">
                        <a href="{{route('admin.company.index')}}"
                           data-href="{{route('admin.company.index')}}"
                           class="nav-link {{request()->routeIs('admin.company.*') ? 'active' : ''}}">Company</a>
                    </li>
                @endcan

            @can('list_branch')
                <li class="nav-item">
                    <a href="{{route('admin.branch.index')}}"
                       data-href="{{route('admin.branch.index')}}"
                       class="nav-link {{request()->routeIs('admin.branch.*') ? 'active' : ''}}">Branch</a>
                </li>
            @endcan

            @can('list_department')
                <li class="nav-item">
                    <a href="{{route('admin.departments.index')}}"
                       data-href="{{route('admin.departments.index')}}"
                       class="nav-link {{request()->routeIs('admin.departments.*') ? 'active' : ''}}">Department</a>
                </li>
            @endcan

            @can('list_kpi')
                <li class="nav-item">
                    <a href="{{route('admin.manageKpi.index')}}"
                       data-href="{{route('admin.manageKpi.index')}}"
                       class="nav-link {{request()->routeIs('admin.manageKpi.*') ? 'active' : ''}}">Manage KPI</a>
                </li>
            @endcan

            @can('list_scoreKpi')
                <li class="nav-item">
                    <a href="{{route('admin.scoreKpi.index')}}"
                       data-href="{{route('admin.scoreKpi.index')}}"
                       class="nav-link {{request()->routeIs('admin.scoreKpi.*') ? 'active' : ''}}">Score KPI</a>
                </li>
            @endcan

            @can('list_post')
                <li class="nav-item">
                    <a href="{{route('admin.posts.index')}}"
                       data-href="{{route('admin.posts.index')}}"
                       class="nav-link {{request()->routeIs('admin.posts.*') ? 'active' : ''}}">Post</a>
                </li>
            @endcan
            </ul>
        </div>
    </li>
@endcanany

