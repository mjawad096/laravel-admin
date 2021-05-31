@extends('laravel-admin::layouts.main')

@if(!empty($page_title))
    @php
        $title = $page_title;

        if(!empty($page)){
           $title = "{$page->title} - {$title}";
        }
    @endphp
    @section('title', $title)
@endif

@push('page_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/validation/form-validation.css') }}">
@endpush

@push('css')
    <style>
        .dropzone{
            min-height: 100px;
        }

        .input-image-preview{
            height: 100px;
            /*text-align: center;*/
        }
    </style>
@endpush

@section('main')
    @include('laravel-admin::layouts.header')
    @include('laravel-admin::layouts.sidebar', ['menu_active' => $menu_active ?? 'dashboard'])

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">                
                @if(!empty($page_title))
                    <div class="content-header-left col-md-9 col-12 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-left mb-0">{{ $page_title }}</h2>
                               
                                @if(!empty($breadcrumbs))
                                    <div class="breadcrumb-wrapper col-12">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="/">Home</a>
                                            </li>
                                            @foreach($breadcrumbs as $name => $url)
                                                <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                                                    @if($loop->last || is_null($url))
                                                        {{ $name }}
                                                    @else
                                                        <a href="{{ $url }}">{{ $name }}</a>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ol>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="content-body">

                @yield('content')

            </div>
        </div>
    </div>

    @include('laravel-admin::layouts.footer')
@endsection

@push('js')
    <script src="https://cdn.tiny.cloud/1/plvfgap34uuvzkaly2pp6oeweszgvb15ra0q9ff7jqvwrmdv/tinymce/4/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            tinymce.init({
                selector: '.rich-editor',
                branding: false,
                plugins: 'code link',
                menubar: false,
                toolbar: 'undo redo | styleselect link | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | code',
            });

            tinymce.init({
                selector: '.rich-editor-advance',
                branding: false,
                plugins: 'code link image',
                menubar: false,
                toolbar: 'undo redo | styleselect | link image | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code',
            });
        });
    </script>
@endpush