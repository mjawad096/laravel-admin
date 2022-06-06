<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    
    <meta name="description" content="">
    <meta name="keywords" content="">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title', 'Dashboard') | {{ config('laravel-admin.app_name', config('app.name')) }}
    </title>
    
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.css') }}">
    @stack('vendor_css')
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/semi-dark-layout.css') }}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/toastr.css') }}">

    @stack('page_css')

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" href="{{ asset('app-assets/style.css') }}">

    @foreach(config('laravel-admin.stylesheets', []) as $stylesheet)
        <link rel="stylesheet" href="{{ asset($stylesheet) }}">
    @endforeach
    <!-- END: Custom CSS-->
    
    @livewireStyles

    @stack('css')
</head>
<!-- END: Head-->
<!-- BODY -->
<body class="vertical-layout vertical-menu-modern @yield('body_columns', 2)-columns navbar-floating footer-static @yield('body_extra_classes', 'menu-expanded')" data-open="click" data-menu="vertical-menu-modern" data-col="@yield('body_columns', 2)-columns">

    @yield('main')


    <form method="POST" data-form-logout action="{{ route(config('laravel-admin.routes.user.logout', 'logout')) }}">
        @csrf
    </form>

    <form method="POST" data-form-delete action="">
        @csrf
        @method('delete')
    </form>

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->
    
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    @stack('vendor_js')
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/components.js') }}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    {{-- <script src="{{ asset('app-assets/js/scripts/extensions/toastr.js') }}"></script> --}}
    @stack('page_js')
    <!-- END: Page JS-->

    @foreach(config('laravel-admin.scripts', []) as $script)
        <script src="{{ asset($script) }}" defer></script>
    @endforeach

    @auth()
        <script>
            jQuery(document).ready(function($) {
                $(document).on('click', '[data-logout]', function(event) {
                    event.preventDefault();
                    
                    $('form[data-form-logout]').trigger('submit')
                });
            });
        </script>
    @endauth

    <script>
        jQuery(document).ready(function($) {
            function submit_delete_form(url){
                $('form[data-form-delete]').attr('action', url).trigger('submit').attr('action', '')
            }

            @if(session()->has('message'))
                setTimeout(() => {
                    toastr['{{ session('status') ? 'success' : 'error' }}']('{{ session('message') }}');
                }, 0);
            @endif

            @if ($errors->count())
                setTimeout(() => {
                    toastr.error('The given data was invalid')
                }, 0);
            @endif

            $(document).on('click', '[data-action_button]', function(event) {
                event.preventDefault();
                
                var action = $(this).data('action');

                if(action === 'remove' && confirm('Are you sure? you want to delete this item?')){
                    var action_url = $(this).data('action_url');
                    submit_delete_form(action_url);
                }
            });
        })
    </script>

    @livewireScripts
    @stack('lvjs')

    @stack('js')
</body>
<!--/ BODY -->
</html>
