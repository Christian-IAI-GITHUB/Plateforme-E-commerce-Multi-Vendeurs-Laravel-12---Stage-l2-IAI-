@extends('front.layout.layout')
@section('content')
<!-- Header Carousel Start -->
<!--
    <div id="header-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 610px;">
                 <a href="#"><img class="img-fluid" src="{{ asset('front/images/carousel-1.jpg')}}" alt="Image"></a>
            </div>
            <div class="carousel-item" style="height: 610px;">
                <a href="#"><img class="img-fluid" src="{{ asset('front/images/carousel-2.jpg')}}"  alt="Image"></a>
            </div>
            <div class="carousel-item" style="height: 610px;">
                <a href="#"><img class="img-fluid" src="{{ asset('front/images/carousel-3.jpg')}}" alt="Image"></a>
            </div>     
        </div>
        <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
            <div class="btn btn-dark" style="width: 45px; height: 45px;">
                <span class="carousel-control-prev-icon mb-n2"></span>
            </div>
        </a>
        <a class="carousel-control-next" href="#header-carousel" data-slide="next">
            <div class="btn btn-dark" style="width: 45px; height: 45px;">
                <span class="carousel-control-next-icon mb-n2"></span>
            </div>
        </a>
    </div>
    -->
    <!-- Header Carousel End -->

    <!-- Featured Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Produit de qualité</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0">Livraison gratuite</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Retour sous 14 jours</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Support 24/7</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Featured End -->

    <!-- Categories Start -->
     <!--
    <div class="container-fluid pt-2">
        <div class="row px-xl-5 pb-3">
            @foreach ($categories as $category)
                @php
                    $image = !empty($category['image'])
                             ? asset('front/images/categories/'.$category['image'])
                             : asset('front/images/products/no-image.jpg');
                @endphp
                <div class="col-lg-4 col-md-6 pb-1">
                    <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                        <p class="text-right"> {{ $category['product_count'] }} Produits</p>
                        <a href="{{ url('category/'.$category['url']) }}" class="cat-img position-relative overflow-hidden mb-3">
                            <img class="img-fluid" src="{{ asset($image)}}" alt="{{ $category['name'] }}">
                        </a>
                        <h5 class="font-weight-semi-bold m-0">{{ $category['name'] }}</h5>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    -->
    <!-- Categories End -->

    <!-- Offer Start -->
    <!--
    <div class="container-fluid offer pt-2">
        <div class="row px-xl-5">
            <div class="col-md-6 pb-4">
                <div class="position-relative bg-secondary text-center text-md-right text-white mb-2 py-5 px-5">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="text-uppercase text-primary mb-3">20 % de réduction sur la commande entière</h5>
                        <h1 class="mb-4 font-weight-semi-bold">Collection d'été</h1>
                        <a href="" class="btn btn-outline-primary py-md-2 px-md-3">Achetez maintenant</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pb-4">
                <div class="position-relative bg-secondary text-center text-md-left text-white mb-2 py-5 px-5">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="text-uppercase text-primary mb-3">10 % de réduction sur la commande entière</h5>
                        <h1 class="mb-4 font-weight-semi-bold">Collection d'Hiver</h1>
                        <a href="" class="btn btn-outline-primary py-md-2 px-md-3">Achetez maintenant</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    -->
    <!-- Offer End -->

    <!-- Products Start -->
    <div class="container-fluid pt-3">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">Produits en vedette</span></h2>
        </div>
        <div class="row px-xl-5 pb-3">
<!--             @foreach ($featuredProducts as $product)
                @php
                    $fallbackImage = asset('front/images/products/no-image.jpg');
                    $image = !empty($product['main_image'])
                             ? asset('product-image/medium/'.$product['main_image'])
                             :(!empty($product['product_images'][0]['image'])
                               ? asset('product-image/medium/'.
                               $product['product_images'][0]['image'])
                               : $fallbackImage);
                @endphp
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    <div class="card product-item border-0 mb-4">
                        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                            <a href="#"> <img class="img-fluid w-100" src="{{ $image}}" alt="{{ $product['product_name'] }}"></a>
                        </div>
                        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                            <h6 class="text-truncate mb-3"> {{ $product['product_name'] }} </h6>
                            <div class="d-flex justify-content-center">
                                <h6>{{ $product['final_price'] }} FCFA</h6>
                                @if ($product['product_discount'] > 0)
                                    <h6 class="text-muted ml-2">
                                        <del>{{ $product['product_price'] }} FCFA</del>
                                    
                                @endif
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between bg-light border">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm text-dark p-0">
                            <i class="fas fa-eye text-primary mr-1"></i>Voir les Détails
                        </a>                        
                        <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Ajouter au panier</a>
                        </div>
                    </div>
                </div>
            @endforeach -->
            
            @foreach ($featuredProducts as $product)
    @php
        $fallbackImage = asset('front/images/no-image.jpg');
        $image = '';
        
        // Vérification de l'image principale - support tableau et objet
        if (!empty($product['main_image']) || (!empty($product->main_image ?? null))) {
            $mainImage = $product['main_image'] ?? $product->main_image;
            $image = asset('product-image/medium/' . $mainImage);
        } 
        // Vérification des images du produit
        elseif ((!empty($product['product_images']) && count($product['product_images']) > 0 && !empty($product['product_images'][0]['image'])) ||
                (!empty($product->product_images ?? null) && count($product->product_images) > 0 && !empty($product->product_images[0]->image ?? null))) {
            $firstImage = $product['product_images'][0]['image'] ?? $product->product_images[0]->image;
            $image = asset('product-image/medium/' . $firstImage);
        } 
        // Image par défaut
        else {
            $image = $fallbackImage;
        }
    @endphp
    
    <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
        <div class="card product-item border-0 mb-4">
            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                <a href="{{ route('products.show', $product['id'] ?? $product->id) }}"> 
                    <img class="img-fluid w-100" src="{{ $image }}" alt="{{ $product['product_name'] ?? $product->product_name }}" loading="lazy">
                </a>
            </div>
            
            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                <h6 class="text-truncate mb-3">{{ $product['product_name'] ?? $product->product_name }}</h6>
                <div class="d-flex justify-content-center">
                    <h6>{{ number_format($product['final_price'] ?? $product->final_price, 0, ',', ' ') }} FCFA</h6>
                    @if (($product['product_discount'] ?? $product->product_discount ?? 0) > 0)
                        <h6 class="text-muted ml-2">
                            <del>{{ number_format($product['product_price'] ?? $product->product_price, 0, ',', ' ') }} FCFA</del>
                        </h6>
                    @endif
                </div>
            </div>
            
            <div class="card-footer d-flex justify-content-between bg-light border">
                <a href="{{ route('products.show', is_array($product) ? $product['id'] : $product->id) }}" class="btn btn-sm text-dark p-0">
                    <i class="fas fa-eye text-primary mr-1"></i>Voir les Détails
                </a>
                <button class="btn btn-sm btn-primary js-add-to-cart" data-id="{{ is_array($product) ? $product['id'] : $product->id }}">Ajouter au panier</button>


                
            </div>
        </div>
    </div>
@endforeach
        </div>
    </div>
    <!-- Products End -->

    <!-- Subscribe Start -->
    <div class="container-fluid bg-secondary my-2">
        <div class="row justify-content-md-center py-5 px-xl-5">
            <div class="col-md-6 col-12 py-5">
                <div class="text-center mb-2 pb-2">
                    <h2 class="section-title px-5 mb-3"><span class="bg-secondary px-2">Restez informé</span></h2>
                    <p>Restez informé des derniers produits, offres et promotions exclusives !</p>
                </div>
                <!--
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control border-white p-4" placeholder="Entrez votre e-mail">
                        <div class="input-group-append">
                            <button class="btn btn-primary px-4">Abonnez-vous</button>
                        </div>
                    </div>
                </form>
                -->
            </div>
        </div>
    </div>
    <!-- Subscribe End -->

    <!-- Products Start -->
    <div class="container-fluid pt-4">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">Nouveaux Arrivages</span></h2>
        </div>
        <div class="row px-xl-5 pb-3">
            <!-- @foreach ($newArrivalProducts as $product)
                @php
                    $fallbackImage = asset('front/images/products/no-image.jpg');
                    $image = !empty($product['main_image'])
                             ? asset('product-image/medium/'.$product['main_image'])
                             :(!empty($product['product_images'][0]['image'])
                               ? asset('product-image/medium/'.
                               $product['product_images'][0]['image'])
                               : $fallbackImage);
                @endphp
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    <div class="card product-item border-0 mb-4">
                        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                            <img class="img-fluid w-100" src="{{ $image}}" alt="{{ $product['product_name'] }}">
                        </div>
                        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                            <h6 class="text-truncate mb-3">{{ $product['product_name'] }}</h6>
                            <div class="d-flex justify-content-center">
                                <h6>{{ $product['final_price'] }} FCFA</h6>
                                @if ($product['product_discount'] > 0)
                                    <h6 class="text-muted ml-2"><del>{{ $product['product_price'] }}</del></h6>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between bg-light border">
<a href="{{ route('products.show', $product) }}" class="btn btn-sm text-dark p-0">
    <i class="fas fa-eye text-primary mr-1"></i>Voir les Détails
</a>                        <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                        </div>
                    </div>
                </div>
            @endforeach -->
        
            @foreach ($newArrivalProducts as $product)
    @php
        $fallbackImage = asset('front/images/no-image.jpg');
        $image = '';
        
        // Vérification de l'image principale - support tableau et objet
        if (!empty($product['main_image']) || (!empty($product->main_image ?? null))) {
            $mainImage = $product['main_image'] ?? $product->main_image;
            $image = asset('product-image/medium/' . $mainImage);
        } 
        // Vérification des images du produit
        elseif ((!empty($product['product_images']) && count($product['product_images']) > 0 && !empty($product['product_images'][0]['image'])) ||
                (!empty($product->product_images ?? null) && count($product->product_images) > 0 && !empty($product->product_images[0]->image ?? null))) {
            $firstImage = $product['product_images'][0]['image'] ?? $product->product_images[0]->image;
            $image = asset('product-image/medium/' . $firstImage);
        } 
        // Image par défaut
        else {
            $image = $fallbackImage;
        }
    @endphp
    
    <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
        <div class="card product-item border-0 mb-4">
            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                <a href="{{ route('products.show', $product['id'] ?? $product->id) }}">
                    <img class="img-fluid w-100" src="{{ $image }}" alt="{{ $product['product_name'] ?? $product->product_name }}" loading="lazy">
                </a>
            </div>
            
            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                <h6 class="text-truncate mb-3">{{ $product['product_name'] ?? $product->product_name }}</h6>
                <div class="d-flex justify-content-center">
                    <h6>{{ number_format($product['final_price'] ?? $product->final_price, 0, ',', ' ') }} FCFA</h6>
                    @if (($product['product_discount'] ?? $product->product_discount ?? 0) > 0)
                        <h6 class="text-muted ml-2">
                            <del>{{ number_format($product['product_price'] ?? $product->product_price, 0, ',', ' ') }} FCFA</del>
                        </h6>
                    @endif
                </div>
            </div>
            
            <div class="card-footer d-flex justify-content-between bg-light border">
            <a href="{{ route('products.show', is_array($product) ? $product['id'] : $product->id) }}" class="btn btn-sm text-dark p-0">
                <i class="fas fa-eye text-primary mr-1"></i>Voir les Détails
            </a>

            </div>
        </div>
    </div>
@endforeach
        </div>
    </div>
    <!-- Products End -->
@push('scripts')
<script>
document.addEventListener('click', async (e)=>{
    if(e.target.classList.contains('js-add-to-cart')){
        const id = e.target.dataset.id;
        const res = await fetch('{{ route('cart.add') }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({product_id:id, quantity:1})});
        if(res.ok){
            if(window.refreshCartCount){ refreshCartCount(); }
            e.target.textContent = 'Ajouté';
            setTimeout(()=>{ e.target.textContent = 'Ajouter au panier'; }, 1000);
        }
    }
});
</script>
@endpush

    <!-- Vendor Start -->
    {{-- <div class="container-fluid py-2">
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel vendor-carousel">
                    <div class="vendor-item border p-4">
                        <img src="{{ asset('front/images/stackdevelopers_logo.png')}}" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="{{ asset('front/images/stackdevelopers-logo.png')}}" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="{{ asset('front/images/stackdevelopers_logo.png')}}" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="{{ asset('front/images/stackdevelopers-logo.png')}}" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="{{ asset('front/images/stackdevelopers_logo.png')}}" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="{{ asset('front/images/stackdevelopers-logo.png')}}" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="{{ asset('front/images/stackdevelopers_logo.png')}}" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="{{ asset('front/images/stackdevelopers-logo.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Vendor End -->
     @endsection