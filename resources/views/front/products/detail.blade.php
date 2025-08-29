@extends('front.layout.layout')

@section('content')
    <!-- En-tête de la page -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 150px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">{{ $product->product_name }}</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="{{ url('/') }}">Accueil</a></p>
                <p class="m-0 px-2">-</p>
                {{-- <p class="m-0"><a href="{{ route('products.all') }}">Produits</a></p>
                <p class="m-0 px-2">-</p> --}}
                <p class="m-0">{{ $product->product_name }}</p>
            </div>
        </div>
    </div>
    <!-- Fin en-tête de la page -->

    <!-- Détails du produit -->
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <!-- Carrousel d'images -->
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner border">
                        @if($product->product_images && count($product->product_images) > 0)
                            @foreach($product->product_images as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img class="w-100 h-100"
                                         src="{{ asset('product-image/large/' . ($image['image'] ?? $image->image)) }}"
                                         alt="{{ $product->product_name }}"
                                         style="height: 300px; object-fit: contain;"
                                         loading="lazy">
                                </div>
                            @endforeach
                        @elseif($product->main_image)
                            <div class="carousel-item active">
                                <img class="w-100 h-100"
                                     src="{{ asset('product-image/large/' . $product->main_image) }}"
                                     alt="{{ $product->product_name }}"
                                     style="height: 300px; object-fit: contain;"
                                     loading="lazy">
                            </div>
                        @else
                            <div class="carousel-item active">
                                <img class="w-100 h-100"
                                     src="{{ asset('front/images/no-image.jpg') }}"
                                     alt="{{ $product->product_name }}"
                                     style="height: 300px; object-fit: contain;">
                            </div>
                        @endif
                    </div>
                    
                    @if($product->product_images && count($product->product_images) > 1)
                        <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                            <i class="fa fa-2x fa-angle-left text-dark"></i>
                        </a>
                        <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                            <i class="fa fa-2x fa-angle-right text-dark"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Informations du produit -->
            <div class="col-lg-7 pb-5">
                <h3 class="font-weight-semi-bold">{{ $product->product_name }}</h3>
                
                <!-- Évaluation -->
                <div class="d-flex mb-3">
                    <div class="text-primary mr-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($product->average_rating ?? 0))
                                <small class="fas fa-star"></small>
                            @elseif($i == ceil($product->average_rating ?? 0) && ($product->average_rating - floor($product->average_rating)) >= 0.5)
                                <small class="fas fa-star-half-alt"></small>
                            @else
                                <small class="far fa-star"></small>
                            @endif
                        @endfor
                    </div>
                </div>
                
                <!-- Prix -->
                <div class="mb-4">
                    @if($product->product_discount > 0)
                        <h3 class="font-weight-semi-bold text-primary mb-0">{{ number_format($product->final_price, 0, ',', ' ') }} FCFA</h3>
                        <small class="text-muted"><del>{{ number_format($product->product_price, 0, ',', ' ') }} FCFA</del></small>
                        <span class="badge badge-danger ml-2">
                            -{{ round($product->product_discount) }}%
                        </span>
                    @else
                        <h3 class="font-weight-semi-bold mb-0">{{ number_format($product->final_price, 0, ',', ' ') }} FCFA</h3>
                    @endif
                </div>
                
                <!-- Code produit -->
                <div class="mb-3">
                    <p class="mb-1"><strong>Code produit :</strong> {{ $product->product_code }}</p>
                    @if($product->group_code ?? false)
                        <p class="mb-1"><strong>Code groupe :</strong> {{ $product->group_code }}</p>
                    @endif
                </div>
                
                <!-- Description courte -->
                <p class="mb-4">{{ Str::limit(strip_tags($product->description ?? 'Aucune description disponible.'), 200) }}</p>
                
                <!-- Couleur du produit -->
                @if($product->product_color ?? false)
                    <div class="d-flex align-items-center mb-4">
                        <p class="text-dark font-weight-medium mb-0 mr-3">Couleur :</p>
                        <div class="d-flex flex-wrap">
                            <span class="badge badge-info">{{ $product->product_color }}</span>
                            @if(($product->family_color ?? false) && $product->family_color != $product->product_color)
                                <span class="badge badge-secondary ml-2">Famille : {{ $product->family_color }}</span>
                            @endif
                        </div>
                    </div>
                @endif
                
                <!-- Tailles disponibles -->
                @if(isset($product->sizes) && $product->sizes->count() > 0)
                    <div class="d-flex mb-3">
                        <p class="text-dark font-weight-medium mb-0 mr-3">Tailles :</p>
                        <form>
                            @foreach($product->sizes as $size)
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="size-{{ $size->id }}" name="size" value="{{ $size->id }}">
                                    <label class="custom-control-label" for="size-{{ $size->id }}">{{ $size->size }}</label>
                                </div>
                            @endforeach
                        </form>
                    </div>
                @endif
                
                <!-- Stock -->
                <div class="mb-3">
                    @if(($product->stock ?? 0) > 0)
                        <span class="badge badge-success">En stock ({{ $product->stock }} disponibles)</span>
                    @else
                        <span class="badge badge-danger">Rupture de stock</span>
                    @endif
                </div>
                
                <!-- Poids du produit -->
                @if(($product->product_weight ?? 0) > 0)
                    <div class="mb-3">
                        <p class="mb-1"><strong>Poids :</strong> {{ $product->product_weight }} kg</p>
                    </div>
                @endif
                
               
                
                <div class="mb-3 d-flex align-items-center">
                    <div class="input-group quantity mr-2" style="width:120px;">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary btn-minus">-</button>
                        </div>
                        <input type="text" id="detail-qty" class="form-control text-center" value="1" max="{{ $product->stock ?? 999 }}" readonly>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary btn-plus">+</button>
                        </div>
                    </div>
                    <button class="btn btn-primary js-add-to-cart" data-id="{{ $product->id }}">
                        <i class="fa fa-cart-plus mr-1"></i> Ajouter au panier
                    </button>
                </div>

                <!-- Partage social -->
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Partager :</p>
                    <div class="d-inline-flex">
                        <a class="text-dark px-2" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-dark px-2" href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->product_name) }}" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="text-dark px-2" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a class="text-dark px-2" href="https://pinterest.com/pin/create/button/?url={{ urlencode(request()->url()) }}&description={{ urlencode($product->product_name) }}" target="_blank">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Onglets d'informations -->
        <div class="row px-xl-5">
            <div class="col">
                <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#tab-description">Description</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-caracteristiques">Caractéristiques</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-entretien">Entretien</a>
                    @if($product->product_video ?? false)
                        <a class="nav-item nav-link" data-toggle="tab" href="#tab-video">Vidéo</a>
                    @endif
                </div>
                
                <div class="tab-content">
                    <!-- Description -->
                    <div class="tab-pane fade show active" id="tab-description">
                        <h4 class="mb-3">Description du produit</h4>
                        <div>
                            {!! $product->description ?? 'Aucune description détaillée disponible.' !!}
                        </div>
                        
                        @if($product->search_keywords ?? false)
                            <div class="mt-4">
                                <h6>Mots-clés :</h6>
                                <p class="text-muted">{{ $product->search_keywords }}</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Caractéristiques -->
                    <div class="tab-pane fade" id="tab-caracteristiques">
                        <h4 class="mb-3">Caractéristiques</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>Catégorie :</strong> {{ $product->category->category_name ?? 'Non spécifiée' }}</li>
                                    @if(isset($product->brand) && $product->brand)
                                        <li><strong>Marque :</strong> {{ $product->brand->brand_name }}</li>
                                    @endif
                                    <li><strong>Code produit :</strong> {{ $product->product_code }}</li>
                                    @if($product->product_color ?? false)
                                        <li><strong>Couleur :</strong> {{ $product->product_color }}</li>
                                    @endif
                                    @if(($product->product_weight ?? 0) > 0)
                                        <li><strong>Poids :</strong> {{ $product->product_weight }} kg</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    @if($product->fabric ?? false)
                                        <li><strong>Tissu :</strong> {{ $product->fabric }}</li>
                                    @endif
                                    @if($product->pattern ?? false)
                                        <li><strong>Motif :</strong> {{ $product->pattern }}</li>
                                    @endif
                                    @if($product->sleeve ?? false)
                                        <li><strong>Manche :</strong> {{ $product->sleeve }}</li>
                                    @endif
                                    @if($product->fit ?? false)
                                        <li><strong>Coupe :</strong> {{ $product->fit }}</li>
                                    @endif
                                    @if($product->occasion ?? false)
                                        <li><strong>Occasion :</strong> {{ $product->occasion }}</li>
                                    @endif
                                    @if(($product->product_gst ?? 0) > 0)
                                        <li><strong>TVA :</strong> {{ $product->product_gst }}%</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Entretien -->
                    <div class="tab-pane fade" id="tab-entretien">
                        <h4 class="mb-3">Instructions d'entretien</h4>
                        <div>
                            {!! $product->wash_care ?? 'Aucune instruction d\'entretien spécifiée.' !!}
                        </div>
                    </div>
                    
                    <!-- Vidéo -->
                    @if($product->product_video ?? false)
                        <div class="tab-pane fade" id="tab-video">
                            <h4 class="mb-3">Vidéo du produit</h4>
                            <div class="embed-responsive embed-responsive-16by9">
                                <video class="embed-responsive-item" controls>
                                    <source src="{{ asset('product-video/' . $product->product_video) }}" type="video/mp4">
                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                </video>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Fin détails du produit -->
@push('scripts')
<script>
document.addEventListener('click', async (e)=>{
    if(e.target.classList.contains('js-add-to-cart')){
        const id = e.target.dataset.id;
        const qtyEl = document.getElementById('detail-qty');
        const q = qtyEl ? parseInt(qtyEl.value,10) || 1 : 1;
        const res = await fetch('{{ route('cart.add') }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({product_id:id, quantity:q})});
        if(res.ok){ if(window.refreshCartCount){ refreshCartCount(); } }
    }
});
</script>
<script>
// plus/minus for detail qty
document.addEventListener('click', function(e){
    if(e.target.classList.contains('btn-plus')){
        const el = document.getElementById('detail-qty');
        const max = parseInt(el.getAttribute('max')) || 999;
        let v = parseInt(el.value)||1; if(v<max) el.value = v+1;
    }
    if(e.target.classList.contains('btn-minus')){
        const el = document.getElementById('detail-qty');
        let v = parseInt(el.value)||1; if(v>1) el.value = v-1;
    }
});
</script>
@endpush

    <!-- Produits similaires -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="container-fluid py-5">
            <div class="row px-xl-5">
                <div class="col">
                    <div class="owl-carousel related-carousel">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="card product-item border-0">
                                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                    @php
                                        $relatedImage = '';
                                        if (!empty($relatedProduct->main_image)) {
                                            $relatedImage = asset('product-image/medium/' . $relatedProduct->main_image);
                                        } elseif (!empty($relatedProduct->product_images) && count($relatedProduct->product_images) > 0) {
                                            $relatedImage = asset('product-image/medium/' . $relatedProduct->product_images[0]['image']);
                                        } else {
                                            $relatedImage = asset('front/images/products/no-image.jpg');
                                        }
                                    @endphp
                                    <img class="img-fluid w-100" 
                                         src="{{ $relatedImage }}" 
                                         alt="{{ $relatedProduct->product_name }}"
                                         style="height: 200px; object-fit: cover;"
                                         loading="lazy">
                                </div>
                                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                    <h6 class="text-truncate mb-3">{{ $relatedProduct->product_name }}</h6>
                                    <div class="d-flex justify-content-center">
                                        @if($relatedProduct->product_discount > 0)
                                            <h6>{{ number_format($relatedProduct->final_price, 0, ',', ' ') }} FCFA</h6>
                                            <h6 class="text-muted ml-2"><del>{{ number_format($relatedProduct->product_price, 0, ',', ' ') }} FCFA</del></h6>
                                        @else
                                            <h6>{{ number_format($relatedProduct->final_price, 0, ',', ' ') }} FCFA</h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between bg-light border">
                                    <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-sm text-dark p-0">
                                        <i class="fas fa-eye text-primary mr-1"></i>Voir détails
                                    </a>
                                    <button class="btn btn-sm btn-primary js-add-to-cart" data-id="{{ $relatedProduct->id }}">Ajouter au panier</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Fin produits similaires -->
@endsection

@push('styles')
<style>
    .rating-stars .fas.fa-star {
        color: #ffc107;
    }
    
    .rating-stars .far.fa-star:hover,
    .rating-stars .fas.fa-star:hover {
        color: #ffc107;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Gestion de la quantité
        $('.btn-plus').click(function() {
            var input = $(this).closest('.quantity').find('input');
            var max = parseInt(input.attr('max'));
            var currentVal = parseInt(input.val());
            if (currentVal < max) {
                input.val(currentVal + 1);
            }
        });
        
        $('.btn-minus').click(function() {
            var input = $(this).closest('.quantity').find('input');
            var currentVal = parseInt(input.val());
            if (currentVal > 1) {
                input.val(currentVal - 1);
            }
        });
        
        // Sélection des tailles
        $('input[name="size"]').change(function() {
            $('#selected_size').val($(this).val());
        });
        
        // Système de notation par étoiles
        $('.rating-stars i').click(function() {
            var rating = $(this).data('rating');
            $('#rating-value').val(rating);
            
            $('.rating-stars i').removeClass('fas').addClass('far');
            for (var i = 1; i <= rating; i++) {
                $('.rating-stars i[data-rating="' + i + '"]').removeClass('far').addClass('fas');
            }
        });
        
        // Survol des étoiles
        $('.rating-stars i').hover(function() {
            var rating = $(this).data('rating');
            $('.rating-stars i').removeClass('fas').addClass('far');
            for (var i = 1; i <= rating; i++) {
                $('.rating-stars i[data-rating="' + i + '"]').removeClass('far').addClass('fas');
            }
        }, function() {
            var currentRating = $('#rating-value').val();
            $('.rating-stars i').removeClass('fas').addClass('far');
            if (currentRating) {a
                for (var i = 1; i <= currentRating; i++) {
                    $('.rating-stars i[data-rating="' + i + '"]').removeClass('far').addClass('fas');
                }
            }
        });
    });
</script>
@endpush