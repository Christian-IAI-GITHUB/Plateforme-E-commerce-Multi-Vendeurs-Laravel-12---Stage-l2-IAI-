{{-- resources/views/vendor/products/index.blade.php --}}
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
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            
            <!-- Messages -->
            @if(Session::has('success_message') || session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Succès: </strong> {{ Session::get('success_message') ?? session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(Session::has('error_message') || session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erreur: </strong> {{ Session::get('error_message') ?? session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $title }} ({{ $products->total() }} produits)</h5>
                                <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Ajouter un Produit
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            @if($products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="5%">ID</th>
                                                <th width="10%">Image</th>
                                                <th width="20%">Nom du Produit</th>
                                                <th width="10%">Code</th>
                                                <th width="15%">Catégorie</th>
                                                <th width="10%">Prix</th>
                                                <th width="8%">Stock</th>
                                                <th width="8%">Statut</th>
                                                <th width="8%">Vedette</th>
                                                <th width="15%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($products as $product)
                                                <tr id="product-row-{{ $product->id }}">
                                                    <td>{{ $product->id }}</td>
                                                    <td>
                                                        @if($product->main_image)
                                                            <img src="{{ asset('front/images/products/' . $product->main_image) }}" 
                                                                 alt="{{ $product->product_name }}" 
                                                                 class="img-fluid rounded" 
                                                                 style="max-width: 50px; max-height: 50px;">
                                                        @else
                                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="bi bi-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>{{ $product->product_name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $product->product_code }}</span>
                                                    </td>
                                                    <td>
                                                        {{ $product->category->name ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <strong>{{ number_format($product->final_price, 0, ',', ' ') }} FCFA</strong>
                                                        @if($product->product_discount > 0)
                                                            <br>
                                                            <small class="text-decoration-line-through text-muted">
                                                                {{ number_format($product->product_price, 0, ',', ' ') }} FCFA
                                                            </small>
                                                            <small class="text-danger">(-{{ $product->product_discount }}%)</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($product->stock > 10)
                                                            <span class="badge bg-success">{{ $product->stock }}</span>
                                                        @elseif($product->stock > 0)
                                                            <span class="badge bg-warning">{{ $product->stock }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ $product->stock }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   {{ $product->status == 1 ? 'checked' : '' }}
                                                                   onchange="updateStatus('{{ route('vendor.products.update-status', $product->id) }}', this)">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($product->is_featured == 'Yes')
                                                            <i class="bi bi-star-fill text-warning"></i>
                                                        @else
                                                            <i class="bi bi-star text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('vendor.products.show', $product->id) }}" 
                                                               class="btn btn-sm btn-info" title="Voir">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="{{ route('vendor.products.edit', $product->id) }}" 
                                                               class="btn btn-sm btn-warning" title="Modifier">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-danger" 
                                                                    onclick="confirmDelete('{{ $product->id }}')" title="Supprimer">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                            
                                                            <!-- Formulaire de suppression caché -->
                                                            <form id="delete-form-{{ $product->id }}" 
                                                                  action="{{ route('vendor.products.destroy', $product) }}" 
                                                                  method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $products->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-box-seam" style="font-size: 4rem; color: #ccc;"></i>
                                    <h4 class="mt-3">Aucun produit trouvé</h4>
                                    <p class="text-muted">Vous n'avez pas encore ajouté de produits.</p>
                                    <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Ajouter votre Premier Produit
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Êtes-vous sûr ?</h5>
                    <p class="text-muted">Cette action est irréversible. Le produit sera définitivement supprimé.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('scripts')
<script>
let productToDelete = null;

// Fonction pour confirmer la suppression
function confirmDelete(productId) {
    productToDelete = productId;
    
    // Afficher le modal de confirmation
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Gérer la confirmation de suppression
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (productToDelete) {
        // Cacher le modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();
        
        // Soumettre le formulaire
        document.getElementById('delete-form-' + productToDelete).submit();
    }
});

// Fonction pour mettre à jour le statut
function updateStatus(url, checkbox) {
    const status = checkbox.checked ? 1 : 0;
    
    // Désactiver le checkbox pendant la requête
    checkbox.disabled = true;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher un message de succès (optionnel)
            showToast('Statut mis à jour avec succès', 'success');
        } else {
            // Remettre l'ancien état en cas d'erreur
            checkbox.checked = !checkbox.checked;
            showToast('Erreur lors de la mise à jour du statut', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        // Remettre l'ancien état en cas d'erreur
        checkbox.checked = !checkbox.checked;
        showToast('Erreur réseau', 'error');
    })
    .finally(() => {
        // Réactiver le checkbox
        checkbox.disabled = false;
    });
}

// Fonction pour afficher des notifications toast (optionnel)
function showToast(message, type = 'info') {
    // Créer un toast simple
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    // Ajouter le toast au DOM
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.innerHTML = toastHtml;
    const toastElement = toastContainer.querySelector('.toast');
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    // Supprimer le toast après qu'il se cache
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}
</script>
@endsection