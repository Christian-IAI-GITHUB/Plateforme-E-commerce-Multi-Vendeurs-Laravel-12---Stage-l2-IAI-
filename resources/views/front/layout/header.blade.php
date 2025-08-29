    <?php
    use App\Models\Category;
    //Get category and their Sub category
    $categories = Category::getCategories('Front');
    // echo "<pre>";print_r($categories);die;
    ?>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-secondary py-2 px-xl-5">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark" href="#">FAQs</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="#">Aide</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="#">Support</a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-dark pl-2" href="">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="" class="text-decoration-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-1 mr-0">I</span>DS&nbsp;<span class="text-primary font-weight-bold border px-1 mr-0">e</span>Market</h1>
                </a>
                <!-- <img src="{{ asset('front/images/IDS-Logo-removebg-previewRedimensionner100-100.png') }}" alt="Logo IDS" class="h-10 w-auto"> -->
            </div>
            <div class="col-lg-6 col-6 text-left">
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Rechercher des produits">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 col-6 text-right">
				<!--
                <a href="" class="btn border">
                    <i class="fas fa-heart text-primary"></i>
                    <span class="badge">0</span>
                </a>
				-->
                <a href="{{ url('/cart') }}" class="btn border">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    <span class="badge" id="js-cart-count">0</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid mb-2">
        <div class="row border-top px-xl-5">
            <!-- Left: Categories -->
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100"
                   data-toggle="collapse" href="#navbar-vertical"
                   style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Catégories</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light"
                     id="navbar-vertical" style="width: calc(100% - 30px); z-index: 9;">
                    <div class="navbar-nav w-100">
                        @foreach ($categories as $category)
                            @if ($category['menu_status'] == 1)
                                @if (count($category['subcategories']) > 0)
                                    <div class="nav-item dropdown">
                                        <a href="{{ url($category['url']) }}" class="nav-link" data-toggle="dropdown">{{ $category['name'] }} 
                                            <i class="fa fa-angle-down float-right mt-1" ></i> 
                                        </a>
                                        <div class="dropdown-menu position-absolute bg-secondary border-0 rounded-0 w-100 m-0">
                                            @foreach ($category['subcategories'] as $subcategory)
                                                @if ($subcategory['menu_status'] == 1)
                                                    <a href="{{ url($subcategory['url']) }}" class="dropdown-item">{{ $subcategory['name'] }}</a>
                                                @endif    
                                            @endforeach
                                        </div>
                                    </div>                                   
                                @else
                                    <a href="{{ url($category['url']) }}" class="nav-item nav-link"> {{ $category['name'] }} </a>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </nav>
            </div>

            <!-- Right: Main Navbar -->
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <h1 class="m-0 display-5 font-weight-semi-bold">
                            <span class="text-primary font-weight-bold border px-1 mr-1">I</span>DS&nbsp;
                            <span class="text-primary font-weight-bold border px-1 mr-1">e</span>Market
                        </h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="{{ url('/') }}" class="nav-item nav-link">Accueil</a>
                            @foreach ($categories as $category)
                                @if ($category['menu_status'] == 1)
                                    @if (count($category['subcategories'])>0)
                                        <div class="nav-item dropdown">
                                            <a href="{{ url($category['url']) }}" class="nav-link dropdown-toggle" data-toggle="dropdown">{{ $category['name'] }}</a>
                                            <div class="dropdown-menu rounded-0 m-0">
                                                @foreach ($category['subcategories'] as $subcategory)
                                                    @if ($subcategory['menu_status'] == 1)
                                                        @if (count($subcategory['subcategories'])>0)
                                                            <div class="dropdown-submenu">
                                                                <a href="{{ url($subcategory['url']) }}" class="dropdown-item">
                                                                    {{ $subcategory['name'] }}
                                                                    <i class="fa fa-angle-right float-right mt-1"></i>
                                                                </a>
                                                                <div class="dropdown-menu">
                                                                    @foreach ($subcategory['subcategories'] as $subsubcategory)
                                                                        @if ($subsubcategory['menu_status'] == 1)
                                                                            <a href="{{ url($subsubcategory['url']) }}" class="dropdown-item">
                                                                                {{ $subsubcategory['name'] }}
                                                                            </a>                                         
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            @else
                                                            <a href="{{ url($subcategory['url']) }}" class="dropdown-item">
                                                                {{ $subcategory['name'] }}
                                                            </a>
                                                        @endif   
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @else
                                        <a href="{{ url($category['url']) }}" class="nav-item nav-link">
                                            {{ $category['name'] }}
                                        </a>
                                    @endif
                                @endif
                            @endforeach
                            <!-- <a href="contact.html" class="nav-item nav-link">Contact</a> -->
                        </div>

                        <div class="navbar-nav ml-auto py-0">
                            @guest
                                <a href="{{ route('login') }}" class="nav-item nav-link">Se connecter</a>
                                <a href="{{ route('register') }}" class="nav-item nav-link">S'inscrire</a>
                            @else
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Mon compte</a>
                                    <div class="dropdown-menu rounded-0 m-0 dropdown-menu-right">
                                        @if(auth()->user()->role === 1)
                                            <a href="{{ route('vendor.dashboard') }}" class="dropdown-item">Tableau de bord</a>
                                        @else
                                            @if (Route::has('dashboard'))
                                                <a href="{{ route('dashboard') }}" class="dropdown-item">Tableau de bord</a>
                                            @endif
                                        @endif
                                        @if (Route::has('profile.edit'))
                                            <a href="{{ route('profile.edit') }}" class="dropdown-item">Mon profil</a>
                                        @endif
                                        <div class="dropdown-divider"></div>
                                        <form method="POST" action="{{ route('logout') }}" class="px-3">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0">Se déconnecter</button>
                                        </form>
                                    </div>
                                </div>
                            @endguest
                        </div>

                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->
    @push('scripts')
    <script>
    async function refreshCartCount(){
        try{
            const res = await fetch('{{ route('cart.count') }}');
            if(res.ok){
                const j = await res.json();
                const el = document.getElementById('js-cart-count');
                if(el) el.textContent = j.count ?? 0;
            }
        }catch(e){}
    }
    document.addEventListener('DOMContentLoaded', refreshCartCount);
    </script>
    @endpush