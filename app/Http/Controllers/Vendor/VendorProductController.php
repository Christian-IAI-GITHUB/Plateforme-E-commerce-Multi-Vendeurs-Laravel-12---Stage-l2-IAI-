<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ProductImage;



class VendorProductController extends Controller
{
    /**
     * Afficher la liste des produits du vendeur
     */
    public function index(Request $request)
    {
        $query = Product::where('user_id', Auth::id())
                       ->with(['category', 'brand']);

        // Filtres
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('featured') && $request->featured !== '') {
            $query->where('is_featured', $request->featured);
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');
        
        $allowedSorts = ['created_at', 'product_name', 'product_price', 'stock', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $sortDir);

        $products = $query->paginate(15)->withQueryString();
        
        // Pour les filtres
        $categories = Category::active()->orderBy('name')->get();
        
        $title = "Mes Produits";
        
        return view('vendor.products.index', compact('products', 'categories', 'title'));
    }

    /**
     * Afficher le formulaire de création d'un produit
     */
    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $title = "Ajouter un Produit";
        
        return view('vendor.products.create', compact('categories', 'brands', 'title'));
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = new Product();
            
            // Données automatiques
            $product->user_id = Auth::id();
            $product->admin_id = Auth::id();
            $product->admin_role = 'vendor';
            
            // Remplir avec les données validées
            $this->fillProductData($product, $request);
            
            // Upload des fichiers
            $this->handleFileUploads($product, $request);
            
            $product->save();
            
            DB::commit();
            
            return redirect()->route('vendor.products.index')
                            ->with('success_message', 'Produit créé avec succès!');
                            
        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->withInput()
                        ->with('error_message', 'Erreur lors de la création du produit: ' . $e->getMessage());
        }
    }

    /**
     * Afficher un produit spécifique
     */
    public function show(Product $product)
    {
        $this->authorizeVendorProduct($product);
        
        $product->load(['category', 'brand']);
        $title = "Détails du Produit";
        
        return view('vendor.products.show', compact('product', 'title'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Product $product)
    {
        $this->authorizeVendorProduct($product);
        
        $categories = Category::active()->orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $title = "Modifier le Produit";
        
        return view('vendor.products.edit', compact('product', 'categories', 'brands', 'title'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(ProductRequest $request, Product $product)
    {
        $this->authorizeVendorProduct($product);
        
        try {
            DB::beginTransaction();
            
            // Remplir avec les données validées
            $this->fillProductData($product, $request);
            
            // Upload des fichiers (avec gestion de remplacement)
            $this->handleFileUploads($product, $request, true);
            
            $product->save();
            
            DB::commit();
            
            return redirect()->route('vendor.products.index')
                            ->with('success_message', 'Produit mis à jour avec succès!');
                            
        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->withInput()
                        ->with('error_message', 'Erreur lors de la mise à jour du produit: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un produit
     */
    // Dans app/Http/Controllers/Vendor/VendorProductController.php
// Ajoutez cette méthode à votre contrôleur existant

    /**
     * Supprimer un produit
     */
    public function destroy(Product $product)
    {
        try {
            // SOLUTION 1 : Utiliser auth()->user()->id
            if ($product->user_id !== Auth::id()) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vous n\'êtes pas autorisé à supprimer ce produit.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Non autorisé.');
            }

            // SOLUTION 2 : Utiliser Auth::id() (plus propre)
            // if ($product->user_id !== Auth::id()) {
            //     // même logique...
            // }

            // SOLUTION 3 : Utiliser Auth::user()->id
            // if ($product->user_id !== Auth::user()->id) {
            //     // même logique...
            // }

            // SOLUTION 4 : Récupérer l'utilisateur une fois
            // $currentUser = auth()->user();
            // if ($product->user_id !== $currentUser->id) {
            //     // même logique...
            // }

            // Supprimer l'image principale si elle existe
            if ($product->main_image) {
                $imagePath = public_path('storage/products/' . $product->main_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Supprimer la vidéo si elle existe
            if ($product->product_video) {
                $videoPath = public_path('storage/products/videos/' . $product->product_video);
                if (file_exists($videoPath)) {
                    unlink($videoPath);
                }
            }

            // Supprimer les images additionnelles si elles existent
           $additionalImages = ProductImage::where('product_id', $product->id)->get();

            foreach ($additionalImages as $image) {
                $imagePath = public_path('storage/products/' . $image->image);

                if (file_exists($imagePath)) {
                    @unlink($imagePath); // le @ permet d'éviter les warnings si le fichier est déjà supprimé ou inaccessible
                }

                $image->delete();
            }

            // Supprimer les attributs du produit si nécessaire
            // \App\Models\ProductAttribute::where('product_id', $product->id)->delete();

            // Supprimer le produit
            $product->delete();

            // Réponse en fonction du type de requête
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produit supprimé avec succès.'
                ]);
            }

            return redirect()->route('vendor.products.index')
                           ->with('success', 'Produit supprimé avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur suppression produit: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                           ->with('error', 'Erreur lors de la suppression du produit.');
        }
    }

    /**
     * Mettre à jour le statut d'un produit via AJAX
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $this->authorizeVendorProduct($product);
            
            $request->validate([
                'status' => 'required|boolean'
            ]);
            
            $product->status = $request->status;
            $product->save();
            
            $statusText = $request->status == 1 ? 'activé' : 'désactivé';
            
            return response()->json([
                'status' => true,
                'message' => "Le statut du produit a été {$statusText} avec succès!"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la mise à jour du statut: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dupliquer un produit
     */
    public function duplicate(Product $product)
    {
        $this->authorizeVendorProduct($product);
        
        try {
            DB::beginTransaction();
            
            $newProduct = $product->replicate();
            $newProduct->product_code = $product->product_code . '_copy_' . time();
            $newProduct->product_name = $product->product_name . ' (Copie)';
            $newProduct->status = 0; // Désactivé par défaut
            $newProduct->created_at = now();
            $newProduct->updated_at = now();
            
            $newProduct->save();
            
            DB::commit();
            
            return redirect()->route('vendor.products.edit', $newProduct)
                            ->with('success_message', 'Produit dupliqué avec succès! Vous pouvez maintenant le modifier.');
                            
        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->with('error_message', 'Erreur lors de la duplication: ' . $e->getMessage());
        }
    }

    /**
     * Méthodes privées d'aide
     */

    /**
     * Vérifier que le produit appartient au vendeur connecté
     */
    private function authorizeVendorProduct(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à ce produit.');
        }
    }

    /**
     * Remplir les données du produit
     */
    private function fillProductData(Product $product, ProductRequest $request)
    {
        $data = $request->validated();
        
        // Calculer automatiquement les prix
        $discount = $data['product_discount'] ?? 0;
        $data['product_discount_amount'] = ($data['product_price'] * $discount) / 100;
        $data['final_price'] = $data['product_price'] - $data['product_discount_amount'];
        
        // Valeurs par défaut
        $data['discount_applied_on'] = $data['discount_applied_on'] ?? 'product';
        $data['sort'] = $data['sort'] ?? 0;
        
        $product->fill($data);
    }

    /**
     * Gérer l'upload des fichiers
     */
    private function handleFileUploads(Product $product, ProductRequest $request, $isUpdate = false)
    {
        // Upload image principale
        if ($request->hasFile('main_image')) {
            // Supprimer l'ancienne image si c'est une mise à jour
            if ($isUpdate && $product->main_image) {
                $this->deleteFile('front/images/products/' . $product->main_image);
            }
            
            $image = $request->file('main_image');
            $imageName = $this->generateFileName($image, 'product');
            
            $image->move(public_path('front/images/products'), $imageName);
            $product->main_image = $imageName;
        }
        
        // Upload vidéo produit
        if ($request->hasFile('product_video')) {
            // Supprimer l'ancienne vidéo si c'est une mise à jour
            if ($isUpdate && $product->product_video) {
                $this->deleteFile('front/videos/products/' . $product->product_video);
            }
            
            $video = $request->file('product_video');
            $videoName = $this->generateFileName($video, 'video');
            
            // Créer le dossier s'il n'existe pas
            if (!file_exists(public_path('front/videos/products'))) {
                mkdir(public_path('front/videos/products'), 0755, true);
            }
            
            $video->move(public_path('front/videos/products'), $videoName);
            $product->product_video = $videoName;
        }
    }

    /**
     * Supprimer les fichiers d'un produit
     */
    private function deleteProductFiles(Product $product)
    {
        if ($product->main_image) {
            $this->deleteFile('front/images/products/' . $product->main_image);
        }
        
        if ($product->product_video) {
            $this->deleteFile('front/videos/products/' . $product->product_video);
        }
    }

    /**
     * Supprimer un fichier
     */
    private function deleteFile($filePath)
    {
        $fullPath = public_path($filePath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    /**
     * Générer un nom de fichier unique
     */
    private function generateFileName($file, $prefix = 'file')
    {
        $extension = $file->getClientOriginalExtension();
        return $prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;
    }

    /**
     * Export des produits du vendeur en CSV
     */
    public function export()
    {
        $products = Product::where('user_id', Auth::id())
                          ->with(['category', 'brand'])
                          ->get();

        $filename = 'mes_produits_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // BOM pour UTF-8
            fputs($file, "\xEF\xBB\xBF");
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom du Produit',
                'Code',
                'Catégorie',
                'Marque',
                'Prix',
                'Remise (%)',
                'Prix Final',
                'Stock',
                'Couleur',
                'Statut',
                'Vedette',
                'Date de Création'
            ], ';');

            // Données
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->product_name,
                    $product->product_code,
                    $product->category->name ?? '',
                    $product->brand->name ?? '',
                    $product->product_price,
                    $product->product_discount,
                    $product->final_price,
                    $product->stock,
                    $product->product_color,
                    $product->status ? 'Actif' : 'Inactif',
                    $product->is_featured == 'Yes' ? 'Oui' : 'Non',
                    $product->created_at->format('d/m/Y H:i')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Statistiques rapides pour le dashboard
     */
    public function quickStats()
    {
        $vendorId = Auth::id();
        
        $stats = [
            'total' => Product::byVendor($vendorId)->count(),
            'active' => Product::byVendor($vendorId)->active()->count(),
            'inactive' => Product::byVendor($vendorId)->inactive()->count(),
            'featured' => Product::byVendor($vendorId)->featured()->count(),
            'out_of_stock' => Product::byVendor($vendorId)->outOfStock()->count(),
            'low_stock' => Product::byVendor($vendorId)->where('stock', '<=', 5)->where('stock', '>', 0)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Mise à jour en lot du statut des produits
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:products,id',
            'status' => 'required|boolean'
        ]);

        try {
            DB::beginTransaction();

            // Vérifier que tous les produits appartiennent au vendeur
            $products = Product::whereIn('id', $request->product_ids)
                             ->where('user_id', Auth::id())
                             ->get();

            if ($products->count() !== count($request->product_ids)) {
                throw new \Exception('Certains produits ne vous appartiennent pas.');
            }

            // Mettre à jour le statut
            Product::whereIn('id', $request->product_ids)
                  ->where('user_id', Auth::id())
                  ->update(['status' => $request->status]);

            DB::commit();

            $statusText = $request->status ? 'activés' : 'désactivés';
            $count = count($request->product_ids);

            return response()->json([
                'status' => true,
                'message' => "{$count} produit(s) {$statusText} avec succès!"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'status' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Suppression en lot des produits
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:products,id'
        ]);

        try {
            DB::beginTransaction();

            // Récupérer les produits du vendeur
            $products = Product::whereIn('id', $request->product_ids)
                             ->where('user_id', Auth::id())
                             ->get();

            if ($products->count() !== count($request->product_ids)) {
                throw new \Exception('Certains produits ne vous appartiennent pas.');
            }

            // Supprimer les fichiers et produits
            foreach ($products as $product) {
                $this->deleteProductFiles($product);
                $product->delete();
            }

            DB::commit();

            $count = $products->count();
            return response()->json([
                'status' => true,
                'message' => "{$count} produit(s) supprimé(s) avec succès!"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'status' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}