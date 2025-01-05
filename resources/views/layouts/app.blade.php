
<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="transparent"
    data-width="default" data-menu-styles="light" data-toggled="close">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') | Tensan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- FAVICON -->
    <link rel="icon" href="https://php.spruko.com/mamix/mamix/assets/images/brand-logos/favicon.ico"
        type="image/x-icon">

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- STYLES CSS -->
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">

    <!-- ICONS CSS -->
    <link href="{{ asset('assets/icon-fonts/icons.css') }}" rel="stylesheet">


    <!-- NODE WAVES CSS -->
    <link href="{{ asset('assets/libs/node-waves/waves.min.css') }}" rel="stylesheet">

    <!-- SIMPLEBAR CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}">

    <!-- PICKER CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/%40simonwep/pickr/themes/nano.min.css') }}">

    <!-- AUTO COMPLETE CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/%40tarekraafat/autocomplete.js/css/autoComplete.css') }}">

    <!-- CHOICES CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}">

    <!-- CHOICES JS -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <!-- MAIN JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

</head>

<body>

    @include('layouts.components.switcher')

    <div id="loader">
        <img src="https://php.spruko.com/mamix/mamix/assets/images/media/loader.svg') }}" alt="">
    </div>

    <div class="page">
        @include('layouts.components.header')

        <div class="modal fade" id="header-responsive-search" tabindex="-1"
            aria-labelledby="header-responsive-search" aria-hidden="true">
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
                    Designed with <span class="bi bi-heart-fill text-danger"></span> by <a
                        href="javascript:void(0);" target="_blank">
                        <span class="fw-medium text-primary">Tensan</span>
                    </a> All
                    rights
                    reserved
                </span>
            </div>
        </footer>
        <!-- END FOOTER -->

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
