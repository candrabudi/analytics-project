<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
    data-header-styles="light" data-menu-styles="light" data-toggled="close">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tensan Tea</title>
    <link rel="icon" href="https://php.spruko.com/mamix/mamix/assets/images/brand-logos/favicon.ico"
        type="image/x-icon">
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icon-fonts/icons.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/authentication-main.js') }}"></script>
    <style>
        .btn-signin {
            background-color: #800000;
            /* Softer, pastel red */
            color: #fff;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .btn-signin:hover {
            background-color: #F6A1B0;
            /* Softer hover effect */
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<body class="coming-soon-main">
    <div class="row authentication coming-soon justify-content-center g-0 my-auto">
        <div class="col-xxl-5 col-xl-5 col-lg-5 col-md-6 col-sm-7 col-11 my-auto">
            <div class="authentication-cover">
                <div class="aunthentication-cover-content text-start rounded bg-primary-gradient">
                    <div class="row justify-content-center mx-0 g-0">
                        <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12">
                            <div>
                                <form method="POST" action="/login/process">
                                    @csrf
                                    <div class="card-body p-5">
                                        <div class="mb-5 text-center">
                                            <a href="">
                                                <img src="{{ asset('assets/images/brand-logos/doorlogo.png') }}"
                                                    alt="logo" class="desktop-dark" style="height: 120px;">
                                            </a>
                                        </div>
                                        <p class="h4 mb-2 fw-semibold text-start text-white">Sign In!</p>
                                        <div class="row gy-3">
                                            <div class="col-xl-12">
                                                <label for="signin-username" class="form-label text-default text-start text-white">Username</label>
                                                <input type="text" class="form-control" id="signin-username" name="username" placeholder="Masukan username" required>
                                            </div>
                                            <div class="col-xl-12 mb-2">
                                                <label for="signin-password" class="form-label text-default text-start text-white">Password</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control" id="signin-password" name="password" placeholder="Masukan password" required>
                                                    <a href="javascript:void(0);" class="show-password-button text-muted"
                                                        onclick="createpassword('signin-password',this)" id="button-addon2">
                                                        <i class="ri-eye-off-line align-middle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid mt-4">
                                            <button type="submit" class="btn btn-signin">Masuk</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/animejs/lib/anime.min.js') }}"></script>
    <script src="{{ asset('assets/js/coming-soon.js') }}"></script>
    <script src="{{ asset('assets/js/show-password.js') }}"></script>
</body>


</html>
