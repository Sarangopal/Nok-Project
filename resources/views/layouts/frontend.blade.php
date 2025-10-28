<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Security --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <meta name="author" content="NOK">
    <meta name="description" content="Explore NOK – our mission, projects, and team. Learn more and get in touch with us today.">
    <meta name="keywords" content="Explore NOK – our mission, projects, and team. Learn more and get in touch with us today.">
    <meta name="robots" content="INDEX,FOLLOW">

    <title>{{ config('app.name', 'NOK – Official Website') }}</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('nokw/assets/img/NOK.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@400;500;600;700&family=Fira+Sans:wght@400;500&display=swap" rel="stylesheet">

    {{-- CSS from /public/nokw/assets/css --}}
    <link rel="stylesheet" href="{{ asset('nokw/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('nokw/assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('nokw/assets/css/layerslider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('nokw/assets/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('nokw/assets/css/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('nokw/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('nokw/assets/css/style1.css') }}">

</head>

<body>
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- App JS (compiled or plain) --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- Extra scripts --}}
    @include('partials.scripts')
    @stack('js')
</body>
</html>
