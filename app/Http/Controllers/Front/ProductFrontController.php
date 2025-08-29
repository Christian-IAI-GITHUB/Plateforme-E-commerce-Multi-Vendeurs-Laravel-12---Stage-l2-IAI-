<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Services\Front\ProductService;
use Illuminate\Support\Facades\Route;
use App\Models\Product;

class ProductFrontController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $url = Route::current()->uri();
        $category = Category::where('url', $url)->where('status', 1)->first();
        if (!$category) {
            abort(404);
        }
        $data = $this->productService->getCategoryListingData($url);

        // if(request()->has('json')){
        //     $view = View('front.products.ajax_products_listing', $data)->render();
        //     return response()->json(['view' => $view]);
        // }
        

        return view('front.products.index',$data);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *//* 
    public function show(string $id)
    {
        //
    } */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
/*     public function show(Product $product)
{
    $relatedProducts = Product::where('category_id', $product->category_id)
                             ->where('id', '!=', $product->id)
                             ->where('status', 1)
                             ->limit(6)
                             ->get();
    
    return view('front.product.detail', compact('product', 'relatedProducts'));
} */
    /**
     * Afficher les détails d'un produit
     */
public function show($id)
{
    // Récupérer le produit par ID
    $product = Product::findOrFail($id);
    //$product = Product::with('reviews')->findOrFail($id);

    // Vérifier que le produit est actif
    if ($product->status != 1) {
        abort(404, 'Produit non disponible');
    }

    // Récupérer les produits similaires de la même catégorie
    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('status', 1)
        ->where('stock', '>', 0)
        ->inRandomOrder()
        ->limit(6)
        ->get();

    // Si pas assez de produits similaires, prendre d'autres produits
    if ($relatedProducts->count() < 6) {
        $additionalProducts = Product::where('id', '!=', $product->id)
            ->where('category_id', '!=', $product->category_id)
            ->where('status', 1)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->limit(6 - $relatedProducts->count())
            ->get();

        $relatedProducts = $relatedProducts->merge($additionalProducts);
    }

    // Calculer la note moyenne (si vous avez un système d'avis)
    if (method_exists($product, 'reviews')) {
        $product->average_rating = $product->reviews()->avg('rating') ?? 0;
    } else {
        $product->average_rating = 0;
    }

    return view('front.products.detail', compact('product', 'relatedProducts'));
}


/* public function allProducts()
{
    $products = Product::where('status', 1)->paginate(12);
    return view('front.products.index', compact('products'));
} */


/**
     * Afficher les détails d'un produit
     */


    /**
     * Afficher tous les produits (nouvelle fonction pour liste générale)
     */
    public function allProducts(Request $request)
    {
        $query = Product::where('status', 1);
        
        // Filtrage par catégorie
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        // Filtrage par marque
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand_id', $request->brand);
        }
        
        // Filtrage par prix
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('final_price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('final_price', '<=', $request->max_price);
        }
        
        // Recherche par mot-clé
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('search_keywords', 'LIKE', "%{$search}%");
            });
        }
        
        // Tri
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('final_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('final_price', 'desc');
                break;
            case 'name':
                $query->orderBy('product_name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('sort', 'asc')->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12);
        
        // Données pour les filtres
        $categories = Category::where('status', 1)->get();
        
        return view('front.products.all', compact('products', 'categories'));
    }

    /**
     * Recherche rapide (pour AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $products = Product::where('status', 1)
                          ->where(function($q) use ($query) {
                              $q->where('product_name', 'LIKE', "%{$query}%")
                                ->orWhere('product_code', 'LIKE', "%{$query}%")
                                ->orWhere('search_keywords', 'LIKE', "%{$query}%");
                          })
                          ->limit(10)
                          ->get(['id', 'product_name', 'final_price', 'main_image']);
        
        return response()->json($products);
    }

    /**
     * Afficher les produits en promotion
     */
    public function promotions()
    {
        $products = Product::where('status', 1)
                          ->where('product_discount', '>', 0)
                          ->orderBy('product_discount', 'desc')
                          ->paginate(12);
        
        return view('front.product.promotions', compact('products'));
    }

    /**
     * Afficher les produits mis en avant
     */
    public function featured()
    {
        $products = Product::where('status', 1)
                          ->where('is_featured', 'Yes')
                          ->paginate(12);
        
        return view('front.products.featured', compact('products'));
    }


}
