<!-- Shop Sidebar Start -->
@include('front.products.filters')
<!-- Shop Sidebar End -->


<!-- Shop Product Start -->
<div class="col-lg-9 col-md-12">
    <div class="row pb-3">
        <div class="col-12 pb-1">
            <div class="mb-3">
                {!! $breadcrumbs ?? '' !!}
                <div class="small text-muted">
                    (Recherche:  {{ count($categoryProducts) }} RÉSULTATS)
                </div>
            </div>
        </div>
    </div>
    <div class="row pb-3">
        <div class="col-12 pb-1">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by name">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>

                <form method="GET" id="sortForm">
                    <div class="dropdown ml-4">
                        <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">Trier par</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button class="dropdown-item" type="submit" name="sort" value="latest" {{ $selectedSort == 'latest' ? 'disabled' : '' }}>Nouveautés</button>
                            <button class="dropdown-item" type="submit" name="sort" value="low_to_high" {{ $selectedSort == 'low_to_high' ? 'disabled' : '' }}>Prix croissant</button>
                            <button class="dropdown-item" type="submit" name="sort" value="high_to_low" {{ $selectedSort == 'high_to_low' ? 'disabled' : '' }}>Prix décroissant</button>
                            <button class="dropdown-item" type="submit" name="sort" value="best_selling" {{ $selectedSort == 'best_selling' ? 'disabled' : '' }}>Meilleures ventes</button>
                            <button class="dropdown-item" type="submit" name="sort" value="featured" {{ $selectedSort == 'featured' ? 'disabled' : '' }}>Produits en vedette</button>
                            <button class="dropdown-item" type="submit" name="sort" value="discounted" {{ $selectedSort == 'discounted' ? 'disabled' : '' }}> Avec Remise </button>
                        </div>
                    </div>
                </form>



            </div>
        </div>
        
        @foreach ($categoryProducts as $product)
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
    
    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
        <div class="card product-item border-0 mb-4">
            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                <img class="img-fluid w-100" src="{{ $image }}" alt="{{ $product['product_name'] ?? $product->product_name }}" loading="lazy">
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
        
        <div class="col-12 pb-1">
            {{ $categoryProducts->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
<!-- Shop Product End -->
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