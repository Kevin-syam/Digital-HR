

<style>
    #nav-search-listing > li.highlight {
        background-color:#e82e5f;
    }
    #nav-search-listing > li:hover{
        background-color: #e82e5f;
    }

    #nav-search-listing > li {
        border-bottom: 1px dashed #f1f1f1;
    }

    #nav-search-listing > li.highlight a,#nav-search-listing > li:hover a  {
        color: white;
    }

    #nav-search-listing > li a {
        text-transform: capitalize;
        color: #232323;
    }
</style>

<!-- partial:partials/_navbar.html -->
<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
{{--        <form class="search-form">--}}
{{--            <div class="input-group">--}}
{{--                <div class="input-group-text">--}}
{{--                    <i data-feather="bell"></i>--}}
{{--                </div>--}}
{{--                <h4 class="me-5">Attendance Application </h4>--}}
{{--            </div>--}}
{{--        </form>--}}

        <form class="search-form mb-0">
            <div class="input-group">
                <div class="input-group-text">
                    <i data-feather="search"></i>
                </div>
                <div id="admin-search-menu">
                        <input class="form-control mt-0"
                               id="nav-search"
                               name="nav-search"
                               type="text"
                               autocomplete="off"
                               placeholder="Search menu(ctrl+q)"
                               aria-label="Search">

                        <div class="card card-admin-search" data-toggle="" style="position: absolute !important;">
                            <ul id="nav-search-listing" class="list-group list-group-flush" >

                            </ul>
                        </div>
                </div>
            </div>
        </form>

        <ul class="navbar-nav">
            <li class="nav-item">
                <button type="button" class="navbar-toggler align-self-center">
{{--                    <span title="Light mood" id="sun">--}}
{{--                        <i class="link-icon" data-feather="sun"></i>--}}
{{--                    </span>--}}

{{--                    <span title="Dark mood" id="moon">--}}
{{--                        <i class="link-icon" data-feather="moon"></i>--}}
                    </span>
                </button>
            </li>

            @can(['list_notification'])
                <li class="nav-item dropdown" id="notificationsNavBar" data-href="{{route('admin.nav-notifications')}}">
                    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="bell"></i>
                        <div class="indicator">
                            <div class="circle"></div>
                        </div>
                    </a>
                    <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
                        <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom ">
                            <a href="" id="navAdminNotificationCreate" data-href="{{route('admin.notifications.create')}}"  class="text-muted"><i class="link-icon" data-feather="plus"></i>  Create Notification </a>
                        </div>


                        <div class="p-1 mt-2" id="notifications-detail">
                            <a class="text-muted p-0 px-3 py-2 " style="font-size: 12px;">Latest Notifications </a>
                        </div>

                        <div class="p-1" id="notifications-detail">


                        </div>

                        <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                            <a href="" id="navAdminNotificationList" data-href="{{route('admin.notifications.index')}}">View all</a>
                        </div>
                    </div>
                </li>
            @endcan
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="wd-30 ht-30 rounded-circle" style="object-fit: cover"
                             src="{{ auth()->user()->avatar ? asset(\App\Models\User::AVATAR_UPLOAD_PATH.auth()->user()->avatar) :
                                    asset('assets/images/img.png') }}"     alt="profile">

                    </a>
                    <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                        <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                            <div class="mb-3">
                                <img class="wd-80 ht-80 rounded-circle" style="object-fit: cover" src="{{asset(\App\Models\User::AVATAR_UPLOAD_PATH.auth()->user()->avatar)}}" alt="">
                            </div>
                            <div class="text-center">
                                <p class="tx-16 fw-bolder">{{ucfirst(auth()->user()->name)}}</p>
                                <p class="tx-12 text-muted">{{(auth()->user()->email)}}</p>
                            </div>
                        </div>
                        <ul class="list-unstyled p-1">

                            <li class="dropdown-item py-2">
                                <a href="{{route('admin.users.show',auth()->user()->id)}}" class="text-body ms-0">
                                    <i class="me-2 icon-md" data-feather="user"></i>
                                    <span>Profile</span>
                                </a>
                            </li>

                            <li class="dropdown-item py-2">
                                <a href="{{route('admin.users.edit', auth()->user()->id)}}" class="text-body ms-0">
                                    <i class="me-2 icon-md" data-feather="edit"></i>
                                    <span>Edit Profile</span>
                                </a>
                            </li>

                            @can('request_leave')
                                <li class="dropdown-item py-2">
                                    <a href="{{route('admin.leave-request.create')}}" class="text-body ms-0">
                                        <i class="me-2 icon-md" data-feather="info"></i>
                                        <span>Request Leave</span>
                                    </a>
                                </li>
                            @endcan

                            <li class="dropdown-item py-2">
                                <a href="{{ route('admin.logout') }}"
                                   onclick="event.preventDefault();
                                                       document.getElementById('logout-form').submit();" class="text-body ms-0">
                                        <i class="me-2 icon-md" data-feather="log-out"> </i>log out
                                </a>
                                  <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                  </form>
                            </li>
                        </ul>
                    </div>
                </li>

        </ul>

    </div>
</nav>
<!-- partial -->








