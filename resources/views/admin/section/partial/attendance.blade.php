@can('list_attendance')
    <li class="nav-item {{ request()->routeIs('admin.attendances.*')  ? 'active' : '' }}">
        <a
            href="{{ route('admin.attendances.index') }}"
            data-href="{{ route('admin.attendances.index') }}"
            class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">Attendance Section</span>
        </a>
    </li>
@endcan
