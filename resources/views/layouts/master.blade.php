<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Digital HR Complete HR Attendance System">
    <meta name="author" content="Digital HR">
    <meta name="keywords" content="Digital HR">

    <title>@yield('title')</title>

    @include('admin.section.head_links')
    @yield('styles')
</head>

<body>
<div id="preloader" >
    @include('admin.section.preloader')
</div>

<div class="main-wrapper">
    @include('admin.section.sidebar')
    <div class="page-wrapper">
        @include('admin.section.nav')

        <div class="page-content">
            @include('admin.section.page_header')
            @yield('main-content')
        </div>

        <!-- partial -->
        @include('admin.section.footer')
    </div>
</div>

@include('admin.section.body_links')

@include('layouts.nav_notification_scripts')
@include('layouts.nav_search_scripts')
@include('layouts.theme_scripts')

@yield('scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>


