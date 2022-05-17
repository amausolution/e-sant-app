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

    <title>{{au_config_partner('PARTNER_TITLE')}} | {{ $title??au_partner('title') }}</title>
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
    <!-- icons -->
   <link href="{{au_file('partner/light/css/all.css')}}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap CSS -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{au_file('partner/assets/css/bootstrap.min.css')}}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/css/font-awesome.min.css')}}">
    <!-- Lineawesome CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/css/line-awesome.min.css')}}">
    <!-- Chart CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/plugins/morris/morris.css')}}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{au_file('partner/assets/plugins/select2/css/select2.min.css')}}">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{au_file('partner/assets/js/html5shiv.min.js')}}"></script>
    <script src="{{au_file('partner/assets/js/respond.min.js')}}"></script>


    <![endif]-->
@section('block_component_css')
        @include('partner.component.css')
    @show
    @stack('styles')
    @livewireStyles
</head>
<!-- END HEAD -->
<body>
<!-- Main Wrapper -->
    <div class="main-wrapper">
        <div id="loader-wrapper">
            <div id="loader">
                <div class="loader-ellips">
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                </div>
            </div>
        </div>
        @yield('main')
    <!-- end page container -->
    <!-- end footer -->
    @livewire('partner.notification')

    </div>

{{--<script src="{{au_file('partner/build/ckeditor.js')}}"></script>--}}
{{--
<script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/decoupled-document/ckeditor.js"></script>
--}}

<!-- jQuery -->
<script src="{{au_file('partner/assets/js/jquery-3.5.1.min.js')}}"></script>
<!-- Bootstrap Core JS -->
<script src="{{au_file('partner/assets/js/popper.min.js')}}"></script>
<script src="{{au_file('partner/assets/js/bootstrap.min.js')}}"></script>
<script src="{{au_file('partner/assets/plugins/select2/js/select2.min.js')}}"></script>
<!-- Slimscroll JS -->
<script src="{{au_file('partner/assets/js/jquery.slimscroll.min.js')}}"></script>
<!-- Chart JS -->
<script src="{{au_file('partner/assets/plugins/morris/morris.min.js')}}"></script>
<script src="{{au_file('partner/assets/plugins/raphael/raphael.min.js')}}"></script>
<script src="{{au_file('partner/assets/js/chart.js')}}"></script>
<!-- Custom JS -->
<script src="{{au_file('partner/assets/js/app.js')}}"></script>

{{--<script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>--}}

<!-- end js include path -->
@stack('scripts')

@section('block_component_script')
    @include('partner.component.script')
@show

@section('block_component_alerts')
    @include('partner.component.alerts')
@show
@livewireScripts
</body>

</html>
