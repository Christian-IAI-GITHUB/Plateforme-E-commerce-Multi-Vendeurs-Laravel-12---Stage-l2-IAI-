{{-- resources/views/vendor/layout/layout.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? "Dashboard Fournisseur" }}</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .main-header {
            background: linear-gradient(90deg, #2c3e50 0%, #34495e 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9) !important;
            padding: 0.75rem 1rem;
            margin: 0 0.25rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff !important;
        }
        .navbar-nav .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: #fff !important;
        }
        .navbar-brand {
            color: #fff !important;
            font-weight: 600;
            font-size: 1.25rem;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .content-wrapper {
            margin-top: 0;
            padding-top: 20px;
        }
        .main-footer {
            margin-left: 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        
        <!-- Navbar Principal -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <div class="container-fluid">
                <!-- Brand/Logo à gauche -->
                <a class="navbar-brand" href="{{ route("vendor.dashboard") }}">
                    <i class="bi bi-shop me-2"></i>Dashboard Fournisseur
                </a>
                
                <!-- Bouton toggle pour mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation principale -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route("vendor.dashboard") }}" class="nav-link {{ request()->routeIs("vendor.dashboard") ? "active" : "" }}">
                                <i class="bi bi-speedometer2 me-1"></i>Tableau de bord
                            </a>
                        </li>
                        
                        <!-- Tous les Produits -->
                        <li class="nav-item">
                            <a href="{{ route("vendor.products.index") }}" class="nav-link {{ request()->routeIs("vendor.products.index") ? "active" : "" }}">
                                <i class="bi bi-list-ul me-1"></i>Tous mes Produits
                            </a>
                        </li>
                        
                        <!-- Ajouter Produit -->
                        <li class="nav-item">
                            <a href="{{ route("vendor.products.create") }}" class="nav-link {{ request()->routeIs("vendor.products.create") ? "active" : "" }}">
                                <i class="bi bi-plus-circle me-1"></i>Ajouter Produit
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Menu utilisateur à droite -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield("content")
        </div>
        
        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <!-- <b>Version</b> 1.0.0 -->
            </div>
            <strong>Dashboard Fournisseur &copy; {{ date("Y") }}.</strong> Tous droits réservés.
        </footer>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personnalisés -->
    @stack("scripts")
    
    <script>
        // Confirmation de suppression
        function confirmDelete(message = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
            return confirm(message);
        }
        
        // Fonction pour changer le statut via AJAX
        function updateStatus(url, status, element) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    // Mettre à jour le badge de statut
                    const badge = element.closest('tr').querySelector('.status-badge');
                    if (status == 1) {
                        badge.className = 'badge bg-success status-badge';
                        badge.textContent = 'Actif';
                        element.checked = true;
                    } else {
                        badge.className = 'badge bg-secondary status-badge';
                        badge.textContent = 'Inactif';
                        element.checked = false;
                    }
                    
                    // Afficher un message de succès (optionnel)
                    showAlert('success', data.message);
                } else {
                    showAlert('error', 'Erreur lors de la mise à jour du statut');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('error', 'Erreur lors de la mise à jour du statut');
            });
        }
        
        // Fonction pour afficher des alertes
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alert = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            // Ajouter l'alerte au début du contenu
            const content = document.querySelector('.content-wrapper .container-fluid');
            if (content) {
                content.insertAdjacentHTML('afterbegin', alert);
                
                // Auto-hide après 3 secondes
                setTimeout(() => {
                    const alertElement = content.querySelector('.alert');
                    if (alertElement) {
                        alertElement.remove();
                    }
                }, 3000);
            }
        }
    </script>
</body>
</html>
