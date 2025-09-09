<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Bank Sampah Digital Cipta Muri adalah solusi inovatif untuk mengubah sampah menjadi tabungan. Dukung gerakan daur ulang, tingkatkan ekonomi sirkular, dan wujudkan lingkungan bersih, sehat, serta berkelanjutan bersama komunitas ramah lingkungan kami.">
        <meta name="keywords" content="bank sampah, daur ulang, lingkungan, tabungan, cipta muri">
        <meta name="author" content="Bank Sampah Digital Cipta Muri">
        
        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="Bank Sampah Digital Cipta Muri">
        <meta property="og:description" content="Ubah Sampah Jadi Tabungan, Wujudkan Lingkungan Bersih dan Berkelanjutan">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:image" content="{{ asset('logo.png') }}">
        
        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Bank Sampah Cipta Muri">
        <meta name="twitter:description" content="Ubah Sampah Jadi Tabungan, Wujudkan Lingkungan Bersih dan Berkelanjutan">
        <meta name="twitter:image" content="{{ asset('logo.png') }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
        
        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
