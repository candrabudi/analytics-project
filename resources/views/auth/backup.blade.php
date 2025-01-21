<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
    data-header-styles="light" data-menu-styles="light" data-toggled="close">

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
        .card.custom-card {
            background: linear-gradient(135deg, #EB4D4B, #FB41A2); /* Softer gradient with lighter shades */
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Softer shadow */
        }
    
        .card-body {
            background-color: #FFFFFF; /* Softer light pink */
            border-radius: 10px;
            padding: 30px;
        }
    
        .text-default {
            color: #800000;
        }
    
        .btn-signin {
            background-color: #800000; /* Softer, pastel red */
            color: #fff;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-align: center;
        }
    
        .btn-signin:hover {
            background-color: #F6A1B0; /* Softer hover effect */
            color: #fff;
            cursor: pointer;
        }
    </style>
    
</head>

<body class="authentication-background">
    <div class="container">
        <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                <div class="card custom-card my-4">

                    <form method="POST" action="/login/process">
                        @csrf
                        <div class="card-body p-5">
                            <div class="mb-5 d-flex justify-content-center">
                                <a href="">
                                    <img src="{{ asset('assets/images/brand-logos/doorlogo.png') }}" alt="logo" class="desktop-dark">
                                </a>
                            </div>
                            <p class="h4 mb-2 fw-semibold">Sign In!</p>
                            <div class="row gy-3">
                                <div class="col-xl-12">
                                    <label for="signin-username" class="form-label text-default">Username</label>
                                    <input type="text" class="form-control" id="signin-username" name="username" placeholder="Masukan username" required>
                                </div>
                                <div class="col-xl-12 mb-2">
                                    <label for="signin-password" class="form-label text-default d-block">Password</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" id="signin-password" name="password" placeholder="Masukan password" required>
                                        <a href="javascript:void(0);" class="show-password-button text-muted"
                                            onclick="createpassword('signin-password',this)" id="button-addon2"><i
                                            class="ri-eye-off-line align-middle"></i></a>
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

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/js/show-password.js') }}"></script>
</body>

</html>
