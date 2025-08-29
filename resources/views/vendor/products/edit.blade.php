{{-- resources/views/vendor/products/edit.blade.php --}}
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
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erreurs de validation!</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }} : {{ $product->product_name }}</h3>
                        </div>
                        
                        <form action="{{ route('vendor.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                
                                <!-- Informations de base -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="product_name" class="form-label">Nom du Produit *</label>
                                            <input type="text" class="form-control" id="product_name" name="product_name" 
                                                   value="{{ old('product_name', $product->product_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="product_code" class="form-label">Code du Produit *</label>
                                            <input type="text" class="form-control" id="product_code" name="product_code" 
                                                   value="{{ old('product_code', $product->product_code) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Catégorie et Marque -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Catégorie *</label>
                                            <select class="form-select" id="category_id" name="category_id" required>
                                                <option value="">Sélectionner une catégorie</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" 
                                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="brand_id" class="form-label">Marque</label>
                                            <select class="form-select" id="brand_id" name="brand_id">
                                                <option value="">Sélectionner une marque</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" 
                                                            {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Couleurs -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="product_color" class="form-label">Couleur du Produit *</label>
                                            <input type="text" class="form-control" id="product_color" name="product_color" 
                                                   value="{{ old('product_color', $product->product_color) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="family_color" class="form-label">Famille de Couleur *</label>
                                            <input type="text" class="form-control" id="family_color" name="family_color" 
                                                   value="{{ old('family_color', $product->family_color) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Prix et Stock -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="product_price" class="form-label">Prix (€) *</label>
                                            <input type="number" step="0.01" class="form-control" id="product_price" 
                                                   name="product_price" value="{{ old('product_price', $product->product_price) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="product_discount" class="form-label">Remise (%)</label>
                                            <input type="number" step="0.01" class="form-control" id="product_discount" 
                                                   name="product_discount" value="{{ old('product_discount', $product->product_discount) }}" min="0" max="100">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="stock" class="form-label">Stock *</label>
                                            <input type="number" class="form-control" id="stock" name="stock" 
                                                   value="{{ old('stock', $product->stock) }}" min="0" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Images -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="main_image" class="form-label">Image Principale</label>
                                            <input type="file" class="form-control" id="main_image" name="main_image" 
                                                   accept="image/*">
                                            @if($product->main_image)
                                                <div class="mt-2">
                                                    <img src="{{ asset('front/images/products/' . $product->main_image) }}" 
                                                         alt="{{ $product->product_name }}" 
                                                         class="img-thumbnail" 
                                                         style="max-width: 150px;">
                                                    <p class="text-muted small mt-1">Image actuelle</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="product_weight" class="form-label">Poids (kg)</label>
                                            <input type="number" step="0.01" class="form-control" id="product_weight" 
                                                   name="product_weight" value="{{ old('product_weight', $product->product_weight) }}" min="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                </div>

                                <!-- Détails du produit -->
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="mb-0">Détails du Produit</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="fabric" class="form-label">Tissu</label>
                                                    <input type="text" class="form-control" id="fabric" name="fabric" 
                                                           value="{{ old('fabric', $product->fabric) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="pattern" class="form-label">Motif</label>
                                                    <input type="text" class="form-control" id="pattern" name="pattern" 
                                                           value="{{ old('pattern', $product->pattern) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="sleeve" class="form-label">Manche</label>
                                                    <input type="text" class="form-control" id="sleeve" name="sleeve" 
                                                           value="{{ old('sleeve', $product->sleeve) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="fit" class="form-label">Coupe</label>
                                                    <input type="text" class="form-control" id="fit" name="fit" 
                                                           value="{{ old('fit', $product->fit) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="occasion" class="form-label">Occasion</label>
                                                    <input type="text" class="form-control" id="occasion" name="occasion" 
                                                           value="{{ old('occasion', $product->occasion) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="wash_care" class="form-label">Instructions de Lavage</label>
                                            <textarea class="form-control" id="wash_care" name="wash_care" rows="3">{{ old('wash_care', $product->wash_care) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO -->
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="mb-0">SEO (Optionnel)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="meta_title" class="form-label">Titre Meta</label>
                                            <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                                   value="{{ old('meta_title', $product->meta_title) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">Description Meta</label>
                                            <input type="text" class="form-control" id="meta_description" name="meta_description" 
                                                   value="{{ old('meta_description', $product->meta_description) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="search_keywords" class="form-label">Mots-clés de Recherche</label>
                                            <input type="text" class="form-control" id="search_keywords" name="search_keywords" 
                                                   value="{{ old('search_keywords', $product->search_keywords) }}"
                                                   placeholder="mot1, mot2, mot3...">
                                        </div>
                                    </div>
                                </div>

                                <!-- Options -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_featured" 
                                                   name="is_featured" value="Yes" 
                                                   {{ old('is_featured', $product->is_featured) == 'Yes' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Produit vedette
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="status" 
                                                   name="status" value="1" 
                                                   {{ old('status', $product->status) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status">
                                                Statut actif
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Mettre à Jour
                                </button>
                                <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Retour
                                </a>
                                <a href="{{ route('vendor.products.show', $product) }}" class="btn btn-info">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection