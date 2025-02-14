<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="transparent"
    data-width="fullwidth" data-menu-styles="light" data-toggled="close">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') | Tensan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="https://php.spruko.com/mamix/mamix/assets/images/brand-logos/favicon.ico"
        type="image/x-icon">
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icon-fonts/icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/node-waves/waves.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/%40simonwep/pickr/themes/nano.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/%40tarekraafat/autocomplete.js/css/autoComplete.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}">
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
   
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    

    <style>
        .colored-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        }

        .toast-body {
            color: white;
        }

        .text-fixed-white {
            color: #fff !important;
        }
    </style>
</head>

<body>

    <div id="solid-successToast" class="toast colored-toast bg-success text-fixed-white fade" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-fixed-white">
            <img class="bd-placeholder-img rounded me-2"
                src="https://php.spruko.com/mamix/mamix/assets/images/brand-logos/toggle-dark.png" alt="...">
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">Success! Data has been inserted.</div>
    </div>

    <div id="solid-errorToast" class="toast colored-toast bg-danger text-fixed-white fade" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-fixed-white">
            <img class="bd-placeholder-img rounded me-2"
                src="https://php.spruko.com/mamix/mamix/assets/images/brand-logos/toggle-dark.png" alt="...">
            <strong class="me-auto">Mamix</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">Error! Failed to insert data.</div>
    </div>

    @include('layouts.components.switcher')

    <div id="loader">
        <img src="https://php.spruko.com/mamix/mamix/assets/images/media/loader.svg') }}" alt="">
    </div>

    <div class="page">
        @include('layouts.components.header')

        <div class="modal fade" id="header-responsive-search" tabindex="-1" aria-labelledby="header-responsive-search"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="input-group">
                            <input type="text" class="form-control border-end-0" placeholder="Search Anything ..."
                                aria-label="Search Anything ..." aria-describedby="button-addon2">
                            <button class="btn btn-primary" type="button" id="button-addon2"><i
                                    class="bi bi-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.components.aside')

        <div class="main-content app-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>


        <footer class="footer mt-auto py-3 bg-white text-center">
            <div class="container">
                <span class="text-muted"> Copyright © <span id="year"></span> <a href="javascript:void(0);"
                        class="text-dark fw-medium">NoxWhisper</a>.
                    Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="javascript:void(0);"
                        target="_blank">
                        <span class="fw-medium text-primary">Tensan</span>
                    </a> All
                    rights
                    reserved
                </span>
            </div>
        </footer>

    </div>

    <div class="scrollToTop">
        <span class="arrow lh-1"><i class="ti ti-caret-up fs-20"></i></span>
    </div>
    <div id="responsive-overlay"></div>

    <script src="{{ asset('assets/libs/%40popperjs/core/umd/popper.min.js') }}"></script>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.js') }}"></script>

    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/%40simonwep/pickr/pickr.es5.min.js') }}"></script>

    <script src="{{ asset('assets/libs/%40tarekraafat/autocomplete.js/autoComplete.min.js') }}"></script>

    <script src="{{ asset('assets/js/sticky.js') }}"></script>

    <script src="{{ asset('assets/js/defaultmenu.js') }}"></script>

    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script src="{{ asset('assets/js/custom-switcher.js') }}"></script>

    @stack('scripts')
</body>

</html>
