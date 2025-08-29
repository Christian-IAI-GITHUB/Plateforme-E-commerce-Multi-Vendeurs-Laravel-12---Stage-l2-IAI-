<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>IDSeMarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Laravel 12, Multi-Vendor E-commerce, Laravel E-commerce Template, Bootstrap Laravel, Laravel Admin Panel, Laravel Marketplace, SiteMakers, Stack Developers">
    <meta name="description" content="Download the SiteMakers Laravel 12 Multi-Vendor E-commerce Template – a clean and scalable Laravel project built with Bootstrap, Blade, and a powerful admin panel. Ideal for learning and prototyping e-commerce websites.">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

   @include('front.layout.styles')
   @stack('styles')
</head>

<body>
   @include('front.layout.header')


   @yield('content')

   @include('front.layout.footer')

   @include('front.layout.scripts')

   @stack('scripts')

</body>

</html>