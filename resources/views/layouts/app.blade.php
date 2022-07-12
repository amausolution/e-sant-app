@php
    $styleDefine = 'partner.theme_define.'.config('partner.theme_default');
@endphp
    <!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
{{--    <title>{{au_config_partner('PARTNER_TITLE')}} | {{ $title??au_partner('title') }}</title>--}}
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
    <!-- icons -->
    <link href="{{au_file('css/all.css')}}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap CSS -->
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{au_file('partner/assets/css/bootstrap.min.css')}}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{au_file('css/fontawesome.css')}}">
    <link rel="stylesheet" href="{{au_file('partner/assets/css/font-awesome.min.css')}}">
    <!-- Lineawesome CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/css/line-awesome.min.css')}}">
    <!-- Chart CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/plugins/morris/morris.css')}}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{au_file('partner/assets/css/select2.min.css')}}">
    @section('block_component_css')
        @include('partner.component.css')
    @show
    @stack('styles')
    @inertiaHead
    <style>
        #nprogress {
            pointer-events: none;
        }

        #nprogress .bar {
            background: #B91C1C;
            position: fixed;
            z-index: 1031;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
        }

        /* Fancy blur effect */
        #nprogress .peg {
            display: block;
            position: absolute;
            right: 0px;
            width: 100px;
            height: 100%;
            box-shadow: 0 0 10px #B91C1C, 0 0 5px #B91C1C;
            opacity: 1.0;

            -webkit-transform: rotate(3deg) translate(0px, -4px);
            -ms-transform: rotate(3deg) translate(0px, -4px);
            transform: rotate(3deg) translate(0px, -4px);
        }

        /* Remove these to get rid of the spinner */
        #nprogress .spinner {
            /*display: block;*/
            position: fixed;
            z-index: 1031;
            top: 5px;
            right: 1px;
            width: 100%;
            height: 100vh;
            background-color: #E5E7EB;
            opacity: 0.5;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        #nprogress .spinner-icon {
            width: 45px;
            height: 45px;
            box-sizing: border-box;
            border: solid 4px transparent;
            border-top-color: #B91C1C;
            border-left-color: #B91C1C;
            border-radius: 50%;
            -webkit-animation: nprogress-spinner 400ms linear infinite;
            animation: nprogress-spinner 400ms linear infinite;
        }

        .nprogress-custom-parent {
            overflow: hidden;
            position: relative;
        }

        .nprogress-custom-parent #nprogress .spinner,
        .nprogress-custom-parent #nprogress .bar {
            position: absolute;
        }

        @-webkit-keyframes nprogress-spinner {
            0%   { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }
        @keyframes nprogress-spinner {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<!-- END HEAD -->

<body>


<div class="main-wrapper">

    @inertia
</div>


<!-- jQuery -->
<script src="{{au_file('partner/assets/js/jquery-3.5.1.min.js')}}"></script>
<!-- Bootstrap Core JS -->
<script src="{{au_file('partner/assets/js/popper.min.js')}}"></script>
<script src="{{au_file('partner/assets/js/bootstrap.min.js')}}"></script>
<!-- Slimscroll JS -->
<script src="{{au_file('partner/assets/js/jquery.slimscroll.min.js')}}"></script>

<!-- Custom JS -->
<script src="{{au_file('partner/assets/js/app.js')}}"></script>
<!-- Select2 JS -->
<script src="{{au_file('partner/assets/js/select2.min.js')}}"></script>
{{--<script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>--}}

<!-- end js include path -->
@stack('scripts')
<script>
    window._translations = @json(translate());
</script>


@section('block_component_alerts')
    @include('partner.component.alerts')
@show
</body>
</html>
