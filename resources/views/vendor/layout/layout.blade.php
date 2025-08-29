{{-- resources/views/vendor/layout/layout.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard Fournisseur' }}</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('admin/css/adminlte.min.css') }}">
    
    <style>
        .main-sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        }
        .nav-sidebar .nav-link {
            color: rgba(255,255,255,0.9);
        }
        .nav-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .nav-sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }
        .brand-text {
            color: #fff !important;
            font-weight: 600;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <!-- <i class="bi bi-list"></i> -->
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="{{ route('vendor.dashboard') }}" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i> Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        
        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand -->
           <!--  <a href="{{ route('vendor.dashboard') }}" class="brand-link text-center">
                <span class="brand-text">Dashboard Fournisseur</span>
            </a> -->
            
            <!-- Sidebar -->
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('vendor.dashboard') }}" class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>Tableau de bord</p>
                            </a>
                        </li>
                        
                        <!-- Produits -->
                        <li class="nav-item {{ request()->routeIs('vendor.products.*') ? 'menu-open' : '' }}">
                           <!--  <a href="{{ route('vendor.dashboard') }}" class="nav-link {{ request()->routeIs('vendor.products.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-box-seam"></i>
                                <p>
                                    Mes Produits
                                    <i class="right bi bi-chevron-down"></i>
                                </p>
                            </a> -->
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('vendor.products.index') }}" 
                                       class="nav-link {{ request()->routeIs('vendor.products.index') ? 'active' : '' }}">
                                        <i class="bi bi-circle nav-icon"></i>
                                        <p>Tous les Produits</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('vendor.products.create') }}" 
                                       class="nav-link {{ request()->routeIs('vendor.products.create') ? 'active' : '' }}">
                                        <i class="bi bi-circle nav-icon"></i>
                                        <p>Ajouter Produit</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- Statistiques -->
<!--                         <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-graph-up"></i>
                                <p>Statistiques</p>
                            </a>
                        </li> -->
                        
                        <!-- Paramètres -->
<!--                         <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-gear"></i>
                                <p>Paramètres</p>
                            </a>
                        </li> -->
                        
                    </ul>
                </nav>
            </div>
        </aside>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        
        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <!-- <b>Version</b> 1.0.0 -->
            </div>
            <strong>Dashboard Fournisseur &copy; {{ date('Y') }}.</strong> Tous droits réservés.
        </footer>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin/js/adminlte.min.js') }}"></script>
    
    <!-- Scripts personnalisés -->
    @stack('scripts')
    
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