
@canany(['list_leave_type','list_leave_request'])
    <li class="nav-item {{ request()->routeIs('admin.leaves.*') ||
                        request()->routeIs('admin.leave-request.*') ? 'active' : '' }} ">
        <a class="nav-link" data-bs-toggle="collapse" href="#leaves" data-href="#" role="button" aria-expanded="false" aria-controls="leaves">
            <i class="link-icon" data-feather="bookmark"></i>
            <span class="link-title">Leave</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="{{ request()->routeIs('admin.leaves.*') ||
                    request()->routeIs('admin.leave-request.*') ?'' : 'collapse'  }}" id="leaves">
            <ul class="nav sub-menu">

                @can('list_leave_type')
                    <li class="nav-item">
                        <a
                            href="{{route('admin.leaves.index')}}"
                            data-href="{{route('admin.leaves.index')}}"
                           class="nav-link {{ request()->routeIs('admin.leaves.*') ? 'active' : '' }}">Leave Type</a>
                    </li>
                @endcan

                @can('list_leave_request')
                    <li class="nav-item">
                        <a href="{{route('admin.leave-request.index')}}"
                           data-href="{{route('admin.leave-request.index')}}"
                           class="nav-link {{ request()->routeIs('admin.leave-request.*') ? 'active' : '' }}">Leave Request</a>
                    </li>
                @endcan
            </ul>
        </div>
    </li>
@endcanany
