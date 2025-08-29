{{-- resources/views/vendor/products/show.blade.php --}}
@extends('vendor.layout.layout')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">{{ $title }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.products.index') }}">Produits</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            
            <div class="row">
                <!-- Informations principales -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">{{ $product->product_name }}</h3>
                            <div>
                                @if($product->status == 1)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                                
                                @if($product->is_featured == 'Yes')
                                    <span class="badge bg-warning ms-1">
                                        <i class="bi bi-star-fill"></i> Vedette
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <!-- Image principale -->
                            @if($product->main_image)
                                <div class="text-center mb-4">
                                    <img src="{{ asset('front/images/products/' . $product->main_image) }}" 
                                         alt="{{ $product->product_name }}" 
                                         class="img-fluid rounded shadow"
                                         style="max-height: 400px;">
                                </div>
                            @endif

                            <!-- Informations de base -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Code Produit:</strong></td>
                                            <td>{{ $product->product_code }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Catégorie:</strong></td>
                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Marque:</strong></td>
                                            <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Couleur:</strong></td>
                                            <td>{{ $product->product_color }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Famille de couleur:</strong></td>
                                            <td>{{ $product->family_color }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Prix original:</strong></td>
                                            <td>{{ number_format($product->product_price, 2) }} FCFA</td>
                                        </tr>
                                        @if($product->product_discount > 0)
                                            <tr>
                                                <td><strong>Remise:</strong></td>
                                                <td class="text-danger">{{ $product->product_discount }}%</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Prix final:</strong></td>
                                                <td class="text-success"><strong>{{ number_format($product->final_price, 2) }} FCFA</strong></td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Stock:</strong></td>
                                            <td>
                                                @if($product->stock > 0)
                                                    <span class="badge bg-success">{{ $product->stock }} unités</span>
                                                @else
                                                    <span class="badge bg-danger">Rupture de stock</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Poids:</strong></td>
                                            <td>{{ $product->product_weight }} gramme</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($product->description)
                                <div class="mb-4">
                                    <h5>Description</h5>
                                    <div class="border rounded p-3 bg-light">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                </div>
                            @endif

                            <!-- Détails du produit -->
                            @if($product->fabric || $product->pattern || $product->sleeve || $product->fit || $product->occasion)
                                <div class="mb-4">
                                    <h5>Détails du Produit</h5>
                                    <div class="row">
                                        @if($product->fabric)
                                            <div class="col-md-4 mb-2">
                                                <strong>Tissu:</strong> {{ $product->fabric }}
                                            </div>
                                        @endif
                                        @if($product->pattern)
                                            <div class="col-md-4 mb-2">
                                                <strong>Motif:</strong> {{ $product->pattern }}
                                            </div>
                                        @endif
                                        @if($product->sleeve)
                                            <div class="col-md-4 mb-2">
                                                <strong>Manche:</strong> {{ $product->sleeve }}
                                            </div>
                                        @endif
                                        @if($product->fit)
                                            <div class="col-md-4 mb-2">
                                                <strong>Coupe:</strong> {{ $product->fit }}
                                            </div>
                                        @endif
                                        @if($product->occasion)
                                            <div class="col-md-4 mb-2">
                                                <strong>Occasion:</strong> {{ $product->occasion }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Instructions de lavage -->
                            @if($product->wash_care)
                                <div class="mb-4">
                                    <h5>Instructions de Lavage</h5>
                                    <div class="border rounded p-3 bg-light">
                                        {!! nl2br(e($product->wash_care)) !!}
                                    </div>
                                </div>
                            @endif

                        </div>
                        
                        <div class="card-footer">
                            <a href="{{ route('vendor.products.edit', $product) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                            <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour à la Liste
                            </a>
                            <form method="POST" 
                                  action="{{ route('vendor.products.destroy', $product) }}" 
                                  style="display: inline-block;"
                                  onsubmit="return confirmDelete('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="col-md-4">
                    <!-- Statistiques -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Informations</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Créé le:</small>
                                <br>
                                <strong>{{ $product->created_at->format('d/m/Y à H:i') }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Dernière modification:</small>
                                <br>
                                <strong>{{ $product->updated_at->format('d/m/Y à H:i') }}</strong>
                            </div>
                            @if($product->group_code)
                                <div class="mb-3">
                                    <small class="text-muted">Code groupe:</small>
                                    <br>
                                    <strong>{{ $product->group_code }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- SEO -->
                    @if($product->meta_title || $product->meta_description || $product->search_keywords)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">SEO</h5>
                            </div>
                            <div class="card-body">
                                @if($product->meta_title)
                                    <div class="mb-2">
                                        <small class="text-muted">Titre Meta:</small>
                                        <br>
                                        <strong>{{ $product->meta_title }}</strong>
                                    </div>
                                @endif
                                @if($product->meta_description)
                                    <div class="mb-2">
                                        <small class="text-muted">Description Meta:</small>
                                        <br>
                                        <strong>{{ $product->meta_description }}</strong>
                                    </div>
                                @endif
                                @if($product->search_keywords)
                                    <div class="mb-2">
                                        <small class="text-muted">Mots-clés:</small>
                                        <br>
                                        <strong>{{ $product->search_keywords }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Actions rapides -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Actions Rapides</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <!-- Toggle statut -->
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    data-url="{{ route('vendor.products.update-status', $product->id) }}"
                                    data-new-status="{{ $product->status == 1 ? 0 : 1 }}"
                                    onclick="updateStatusFromButton(this)">
                                    @if ($product->status == 1)
                                        <i class="bi bi-pause-circle"></i> Désactiver
                                    @else
                                        <i class="bi bi-play-circle"></i> Activer
                                    @endif
                                </button>


                                
                                <!-- Dupliquer (à implémenter) -->
                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                    <i class="bi bi-copy"></i> Dupliquer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
// Copiez le contenu du JavaScript ci-dessus ici
function updateStatus(url, newStatus, button) {
    // ... code JavaScript complet
}

function confirmDelete(message) {
    return confirm(message);
}

function updateStatusFromButton(button) {
        const url = button.getAttribute('data-url');
        const newStatus = button.getAttribute('data-new-status');
        updateStatus(url, newStatus, button);
}
</script>
@endsection