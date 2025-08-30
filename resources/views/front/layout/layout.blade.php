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

   <!-- Message de succès pour l'ajout au panier -->
   <div id="cart-success-message" class="alert alert-success" style="display: none; position: fixed; top: 100px; right: 20px; z-index: 9999; min-width: 300px;">
       <i class="fa fa-check-circle mr-2"></i>
       <span id="cart-success-text"></span>
   </div>

   @yield('content')

   @include('front.layout.footer')

   @include('front.layout.scripts')

   @stack('scripts')

   <!-- Script pour le message de succès du panier -->
   <script>
   function showCartSuccessMessage(message) {
       const successDiv = document.getElementById('cart-success-message');
       const messageText = document.getElementById('cart-success-text');
       
       messageText.textContent = message;
       successDiv.style.display = 'block';
       
       // Faire disparaître le message après 3 secondes
       setTimeout(() => {
           successDiv.style.display = 'none';
       }, 3000);
   }
   </script>

</body>

</html>