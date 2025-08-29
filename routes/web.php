<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Response;





//Front Controller Routes
use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\Front\ProductFrontController;
use App\Http\Controllers\Front\CustomerDashboardController;
use App\Models\Category;

/* Route::get('/', function () {
    return view('welcome');
}); */


//vend
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Vendor\VendorProductController;


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';






//Admin Routes
/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('product-image/{size}/{filename}', function ($size, $filename) {
    $sizes = config('image_sizes.product');
        if (!isset($sizes[$size])) {
        abort(404, 'Invalid size.');
    }
        $width = $sizes[$size]['width'];
        $height = $sizes[$size]['height'];
        $path = public_path('front/images/products/' . $filename);
        if (!file_exists($path)) {
        $path = public_path('front/images/no-image.jpg');
    }

        if (!extension_loaded('gd')) {
        return Response::file($path);
    }

        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
/*         $image = $manager->read($path)
        ->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
     }); */
     $image = $manager->read($path)->cover($width, $height);

        $binary = $image->toJpeg(85); // Compression with 85% quality
        return Response::make($binary)->header('Content-Type', 'image/jpeg');
});

Route::prefix('admin')->group(function () {

    // Show login form
    Route::get('login', [AdminController::class, 'create'])->name('admin.login'); 

    // Handle login form submission
    Route::post('login', [AdminController::class, 'store'])->name('admin.login.request');

    Route::group(['middleware' => ['admin']], function () {

        // Dashboard route
        Route::resource('dashboard', AdminController::class)->only(['index']);
        // Dashboard route
        Route::resource('dashboard', AdminController::class)->only(['index'])->names([
        'index' => 'admin.dashboard',]);

        // Display Update Password Page
        Route::get('update-password', [AdminController::class, 'edit'])->name('admin.update-password');
        // Verify Password Route
        Route::post('verify-password', [AdminController::class, 'verifyPassword'])->name('admin.verify.password');  
        // Update Password Route 
        Route::post('admin/update-password', [AdminController::class, 'updatePasswordRequest'])
    ->name('admin.update-password.request');

        // Display Update Admin Details
        Route::get('update-details', [AdminController::class, 'editDetails'])->name('admin.update-details');
        // Update Admin Details Route
        Route::post('update-details', [AdminController::class, 'updateDetails'])->name('admin.update-details.request');
        // Delete Profile Image Route
        Route::post('delete-profile-image',[AdminController::class,'deleteProfileImage']);

        // Sub-Admins
        Route::get('subadmins', [AdminController::class, 'subadmins']);
        Route::post('update-subadmin-status', [AdminController::class, 'updateSubadminStatus']);
        Route::get('add-edit-subadmin/{id?}', [AdminController::class, 'addEditSubadmin']);
        Route::post('add-edit-subadmin/request', [AdminController::class, 'addEditSubadminRequest']);
        Route::get('delete-subadmin/{id}', [AdminController::class, 'deleteSubadmin']);
        Route::get('/update-role/{id}', [AdminController::class, 'updateRole']);
        Route::post('/update-role/request', [AdminController::class, 'updateRoleRequest']);

        // Categories
        Route::resource('categories', CategoryController::class);
        Route::post('update-category-status', [CategoryController::class, 'updateCategoryStatus']);
        Route::post('delete-category-image', [CategoryController::class, 'deleteCategoryImage']);
        Route::post('delete-sizechart-image', [CategoryController::class, 'deleteSizechartImage']);

        // Products
        Route::resource('products', ProductController::class);
        Route::post('update-product-status', [ProductController::class, 'updateProductStatus']);
        Route::post('/product/upload-image', [ProductController::class, 'uploadImage'])->name('product.upload.image');
        Route::post('/product/upload-images', [ProductController::class, 'uploadImages'])->name('product.upload.images');
        Route::post('/product/delete-temp-image', [ProductController::class, 'deleteTempImage'])->name('product.delete.temp.image');
        Route::get('delete-product-image/{id?}', [ProductController::class, 'deleteProductImage']);
        
        Route::post('/product/upload-video', [ProductController::class, 'uploadVideo'])->name('product.upload.video');
        Route::get('delete-product-main-image/{id?}',[ProductController::class,'deleteProductMainImage']);
        Route::get('delete-product-video/{id}',[ProductController::class,'deleteProductVideo']);

        Route::post('/products/update-image-sorting', [ProductController::class, 'updateImageSorting'])->name('admin.products.update-image-sorting');

        Route::post('/products/delete-dropzone-image', [ProductController::class, 'deleteDropzoneImage'])->name('admin.products.delete-image');
        Route::post('/products/delete-temp-image', [ProductController::class, 'deleteTempProductImage'])->name('product.delete.temp.altimage');
        Route::post('/products/delete-temp-video', [ProductController::class, 'deleteTempProductVideo'])->name('product.delete.temp.video');

        // Attributes
        Route::post('update-attribute-status', [ProductController::class, 'updateAttributeStatus']);
        Route::get('delete-product-attribute/{id}', [ProductController::class, 'deleteProductAttribute']);

        // Save Column Orders
        /*Route::post('/save-column-order', [AdminController::class, 'saveColumnOrder']);*/
        Route::post('/save-column-visibility', [AdminController::class, 'saveColumnVisibility']);

        // Brands
        Route::resource('brands', BrandController::class);
        Route::post('update-brand-status', [BrandController::class, 'updateBrandStatus']);

        // Users
        Route::get('users', [UserController::class, 'index']);
        Route::post('users/{user}/toggle', [UserController::class, 'toggleStatus']);
        Route::delete('users/{user}', [UserController::class, 'destroy']);

        // Orders
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{order}', [OrderController::class, 'show']);

        // Admin Logout
        Route::get('logout', [AdminController::class, 'destroy'])->name('admin.logout');  
    });
});


Route::namespace('App\Http\Controllers\Front')->group(function(){
    Route::get('/',[IndexController::class, 'index']);

    $catUrls = Category::where('status', 1)
        ->pluck('url')->toArray();
        foreach ($catUrls as $url) {
            Route::get("/$url", [ProductFrontController::class, 'index']);
        }

});

// Route dashboard pour les clients
Route::get('/dashboard', [CustomerDashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Route dashboard pour les vendeurs
/* Route::get('/vendor/dashboard', function () {
    return view('vendor');
})->middleware(['auth'])->name('vendor');
 */





//vendeur fornisseur

// Dans routes/web.php - Ajouter ces routes après les routes d'authentification



// // Routes pour les vendeurs (fournisseurs)
// Route::middleware(['auth', 'vendor.auth'])->prefix('vendor')->group(function () {
    
//     // Dashboard vendeur
//     Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
    
//     // CRUD Produits pour vendeurs
//     Route::resource('products', VendorProductController::class, [
//         'names' => [
//             'index' => 'vendor.products.index',
//             'create' => 'vendor.products.create',
//             'store' => 'vendor.products.store',
//             'show' => 'vendor.products.show',
//             'edit' => 'vendor.products.edit',
//             'update' => 'vendor.products.update',
//             'destroy' => 'vendor.products.destroy',
//         ]
//     ]);
    
//     // Route pour changer le statut d'un produit
//     Route::post('products/{id}/update-status', [VendorProductController::class, 'updateStatus'])
//          ->name('vendor.products.update-status');
// });

// Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'vendor.auth'])->group(function () {
//     // Routes existantes...
    
//     // Route pour les produits
//     Route::resource('products', App\Http\Controllers\Vendor\VendorProductController::class);
    
//     // Ou si vous préférez définir manuellement :
//     Route::get('products', [App\Http\Controllers\Vendor\VendorProductController::class, 'index'])->name('products.index');
//     Route::get('products/create', [App\Http\Controllers\Vendor\VendorProductController::class, 'create'])->name('products.create');
//     Route::post('products', [App\Http\Controllers\Vendor\VendorProductController::class, 'store'])->name('products.store');
//     Route::get('products/{product}', [App\Http\Controllers\Vendor\VendorProductController::class, 'show'])->name('products.show');
//     Route::get('products/{product}/edit', [App\Http\Controllers\Vendor\VendorProductController::class, 'edit'])->name('products.edit');
//     Route::put('products/{product}', [App\Http\Controllers\Vendor\VendorProductController::class, 'update'])->name('products.update');
//     Route::delete('products/{product}', [App\Http\Controllers\Vendor\VendorProductController::class, 'destroy'])->name('products.destroy');
// });
// // N'oubliez pas d'enregistrer le middleware dans app/Http/Kernel.php
// // Dans $routeMiddleware :
// // 'vendor.auth' => \App\Http\Middleware\VendorAuth::class,

// Routes pour les vendeurs (fournisseurs)
Route::middleware(['auth', 'vendor.auth'])->prefix('vendor')->name('vendor.')->group(function () {
    
    // Dashboard vendeur
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
    
    // CRUD Produits pour vendeurs
    Route::resource('products', VendorProductController::class);
    
    // Route supplémentaire pour changer le statut d'un produit
    Route::post('products/{id}/update-status', [VendorProductController::class, 'updateStatus'])
         ->name('products.update-status');
});

// Routes pour les produits front-end
// Route::get('/products', [Front\ProductController::class, 'index'])->name('products.index');
// Route::get('/product/{product}', [Front\ProductController::class, 'show'])->name('products.show');

// Ou si vous utilisez le ProductController principal :
//Route::get('/products', [ProductController::class, 'index'])->name('products.index');
//Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');
//Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');
//Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');

// Ou si vous utilisez le ProductController principal :
//Route::get('/products', [ProductController::class, 'index'])->name('products.index');
//Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');
//Route::get('/products/all', [ProductController::class, 'allProducts'])->name('products.all');

Route::prefix('products')->name('products.')->group(function () {
    // Liste de tous les produits
    Route::get('/all', [ProductFrontController::class, 'allProducts'])->name('all');

    // Recherche AJAX
    Route::get('/search/ajax', [ProductFrontController::class, 'search'])->name('search');

    // Promotions
    Route::get('/special/promotions', [ProductFrontController::class, 'promotions'])->name('promotions');

    // Produits mis en avant
    Route::get('/special/featured', [ProductFrontController::class, 'featured'])->name('featured');

    // Détails d'un produit (ID passé en paramètre)
    Route::get('/{id}', [ProductFrontController::class, 'show'])->name('show');
});
//Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'form'])->name('checkout.form');
Route::post('/checkout/place', [CheckoutController::class, 'place'])->name('checkout.place');
// Voir une commande (confirmation)
Route::get('/orders/{order}', [CheckoutController::class, 'show'])->name('orders.show')->middleware('auth');
// FedaPay callback (sandbox)
Route::post('/fedapay/callback', function(\Illuminate\Http\Request $request){
    // A affiner : vérifier la signature et mettre à jour le statut
    $payload = $request->all();
    $orderId = $payload['metadata']['order_id'] ?? null;
    if ($orderId) {
        \App\Models\CustomerOrder::where('id', $orderId)->update(['order_status' => 'paid']);
    }
    return response()->json(['ok' => true]);
});
