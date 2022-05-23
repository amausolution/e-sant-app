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
    <!-- Lineawesome CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/css/line-awesome.min.css')}}">
    <!-- Chart CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/plugins/morris/morris.css')}}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{au_file('partner/assets/css/style.css')}}">

    @section('block_component_css')
        @include('partner.component.css')
    @show
    @stack('styles')
    @inertiaHead
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
<!-- Chart JS -->
<script src="{{au_file('partner/assets/plugins/morris/morris.min.js')}}"></script>
<script src="{{au_file('partner/assets/plugins/raphael/raphael.min.js')}}"></script>

<!-- Custom JS -->
<script src="{{au_file('partner/assets/js/app.js')}}"></script>

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
