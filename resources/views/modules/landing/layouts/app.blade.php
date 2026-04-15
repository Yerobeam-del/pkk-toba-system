<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PKK Kabupaten Toba - Portal Aplikasi')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/landing/css/style.css') }}">
    
    @stack('styles')
</head>
<body>
    @include('modules.landing.partials.header')
    @include('modules.landing.partials.floating-btn')
    
    <main>
        @yield('content')
    </main>
    
    @include('modules.landing.partials.footer')
    @include('modules.landing.partials.news-modal')
    
    <script>
        window.Laravel = { csrfToken: '{{ csrf_token() }}' };
    </script>
    
    <!-- Load JS modular -->
    <script src="{{ asset('assets/landing/js/navigation.js') }}"></script>
    <script src="{{ asset('assets/landing/js/hero-slider.js') }}"></script>
    <script src="{{ asset('assets/landing/js/news-handler.js') }}"></script>
    <script src="{{ asset('assets/landing/js/desa-handler.js') }}"></script>
    <script src="{{ asset('assets/landing/js/sk-handler.js') }}"></script>
    <script src="{{ asset('assets/landing/js/template-handler.js') }}"></script>
    <script src="{{ asset('assets/landing/js/animations.js') }}"></script>
    
    @stack('scripts')
</body>
</html>