<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link rel="icon" href="{{ au_file('images/icon.png') }}" type="image/png" sizes="16x16">
    <title>{{au_config_partner('PARTNER_TITLE')}} | {{ $title??au_partner('title') }}</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/css/bootstrap.min.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/css/font-awesome.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/css/style.css')}}">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{au_file('partner/assets/js/html5shiv.min.js')}}"></script>
    <script src="{{au_file('partner/assets/js/respond.min.js')}}"></script>
    <![endif]-->
</head>
<body class="error-page">
<!-- Main Wrapper -->
<div class="main-wrapper">

    <div class="error-box">
        <h1>403</h1>
        <h3><i class="fa fa-warning"></i> {{__('Oops! Access Deny For You')}}</h3>
        <a href="{{au_route_partner('partner.home')}}" class="btn btn-custom">{{__('Back to home')}}</a>
    </div>

</div>
<!-- /Main Wrapper -->

<!-- jQuery -->
<script src="assets/js/jquery-3.5.1.min.js"></script>

<!-- Bootstrap Core JS -->
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Custom JS -->
<script src="assets/js/app.js"></script>

</body>
</html>
