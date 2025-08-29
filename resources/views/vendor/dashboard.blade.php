{{-- resources/views/vendor/dashboard.blade.php --}}
@extends('vendor.layout.layout')
@section('content')
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard Fournisseur</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Accueil</a></li>
                        <li class="breadcrumb-item active"> <a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">
            
            <!-- Bienvenue -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h5><i class="bi bi-person-check"></i> Bienvenue {{ $vendor->name }} !</h5>
                        <p class="mb-0">Gérez vos produits et suivez vos performances depuis votre tableau de bord.</p>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques -->
            <div class="row g-4 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0">{{ $totalProducts }}</h2>
                                    <p class="mb-0">Total Produits</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0">{{ $activeProducts }}</h2>
                                    <p class="mb-0">Produits Actifs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-pause-circle" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0">{{ $inactiveProducts }}</h2>
                                    <p class="mb-0">Produits Inactifs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-star-fill" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0">{{ $featuredProducts }}</h2>
                                    <p class="mb-0">Produits Vedettes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Actions Rapides</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Ajouter un Nouveau Produit
                                </a>
                                <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-list-ul"></i> Voir Tous Mes Produits
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Produits Récents</h5>
                        </div>
                        <div class="card-body">
                            @if($recentProducts->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentProducts as $product)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $product->product_name }}</h6>
                                                <small class="text-muted">{{ $product->product_code }}</small>
                                            </div>
                                            <div>
                                                @if($product->status == 1)
                                                    <span class="badge bg-success">Actif</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactif</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Aucun produit récent.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</main>
@endsection