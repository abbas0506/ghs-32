<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GHS 32/2L</title>
    <link rel="icon" href="{{ asset('/images/logo/ghs-32.png') }}">
    <!-- Fonts -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <!-- <script src="{{ asset('/fonts/bootstrap-icons/bootstrap-icons.min.css') }}"></script> -->
    <!-- chart.js and html5-qrcode moved to end of body to avoid render-blocking -->

    <!-- <script src="{{ asset('js/jsqrcode-combined.min.js') }}"></script> -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    {{-- <link href='https://cdn.boxicons.com/3.0.3/fonts/basic/boxicons.min.css' rel='stylesheet'> --}}
    {{-- <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/swiper.css') }}">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>

    @yield('style')
</head>

<body>
    @yield('header')
    @yield('sidebar')
    @yield('body')
    <script src="{{ asset('js/sweetalert2@10.js') }}"></script>
    <script type="module" src="{{ asset('js/collapsible.js') }}"></script>
    <script type="module" src="{{ asset('js/swiper.js') }}"></script>
    <script type="module" src="{{ asset('js/testimonial.js') }}"></script>
    <script type="module" src="{{ asset('js/flowbite.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    @yield('script')
    @yield('footer')
</body>

</html>
