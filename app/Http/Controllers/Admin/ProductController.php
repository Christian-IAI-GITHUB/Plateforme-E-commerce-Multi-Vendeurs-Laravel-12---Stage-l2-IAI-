<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\ProductService;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ColumnPreference;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{

    protected $productService;

    // Inject ProductService using Constructor
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        Session::put('page', 'products');
        $result = $this->productService->products();
        if ($result['status'] == "error") {
            return redirect('admin/dashboard')->with('error_message', $result['message']);
        }

        $products = $result['products'];
        $productsModule = $result['productsModule'];
        
        $columnPrefs = ColumnPreference::where('admin_id', Auth::guard('admin')->id())
            ->where('table_name', 'products')
            ->first();

        $productsSavedOrder = $columnPrefs ? json_decode($columnPrefs->column_order, true) : null;
        $productsHiddenCols = $columnPrefs ? json_decode($columnPrefs->hidden_columns, true) : [];

        return view('admin.products.index')->with(compact(
            'products',
            'productsModule',
            'productsSavedOrder',
            'productsHiddenCols'
        ));

    }



    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $title = 'Add Product';
        $getCategories = Category::getCategories('Admin');
        // Get All Active Brands
        $brands = Brand::where('status', 1)->get()->toArray();
        return view('admin.products.add_edit_product', compact('title','getCategories','brands'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request) {
        $message = $this->productService->addEditProduct($request);
        return redirect()->route('products.index')->with('success_message', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $title = 'Edit Product';
        $product = Product::with('product_images','attributes')->findOrFail($id);
        $getCategories = Category::getCategories('Admin');
        // Get All Active Brands
        $brands = Brand::where('status', 1)->get()->toArray();
        return view('admin.products.add_edit_product', compact('title' ,  'product' ,  'getCategories','brands'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id) {
        $request->merge(['id' => $id]);
        $message = $this->productService->addEditProduct($request);
        return redirect()->route('products.index')->with('success_message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        $result = $this->productService->deleteProduct($id);
        return redirect()->back()->with('success_message', $result['message']);
    }

    public function updateProductStatus(Request $request){
        if ($request->ajax()) {
            $data = $request->all();
            $status = $this->productService->updateProductStatus($data);
            return response()->json(['status' => $status, 'product_id' => $data['product_id']]);
        }
    }

    public function uploadImage(Request $request){
        if ($request->hasFile('file')) {
            $fileName = $this->productService->handleImageUpload($request->file('file'));
            return response()->json(['fileName' => $fileName]);
        }
        return response()->json(['error' => 'No file uploaded'], 400);
    }
/* 
    public function uploadImages(Request $request){
        if ($request->hasFile('file')) {
            $fileName = $this->productService->handleImageUpload($request->file('file'));
            return response()->json(['fileName' => $fileName]);
        }
        $file = $request->file('file');       
        // Move to temp directory
        $file->move(public_path('temp'), $filename);
        return response()->json([
            'fileName' => $filename,
            'success' => true
        ]);
    } */
   public function uploadImages(Request $request) {
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('temp'), $filename);

        return response()->json([
            'fileName' => $filename,
            'success' => true
        ]);
    }

    return response()->json(['error' => 'No file uploaded'], 400);
}


    public function uploadVideo(Request $request){
        if ($request->hasFile('file')) {
            $fileName = $this->productService->handleVideoUpload($request->file('file'));
            return response()->json(['fileName' => $fileName]);
        }
        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function updateAttributeStatus(Request $request){
        if ($request->ajax()) {
            $data = $request->all();
            $status = $this->productService->updateAttributeStatus($data);
            return response()->json(['status' => $status, 'attribute_id' => $data['attribute_id']]);
        }
    }


    public function deleteProductMainImage($id){
        $message = $this->productService->deleteProductMainImage($id);
        return redirect()->back()->with('success_message', $message);
    }

    public function deleteProductImage($id){
        $message = $this->productService->deleteProductImage($id);
        return redirect()->back()->with('success_message', $message);
    }

    public function deleteProductVideo($id){
        $message = $this->productService->deleteProductVideo($id);
        return redirect()->back()->with('success_message',$message);
    }

    public function deleteProductAttribute($id){
        $message = $this->productService->deleteProductAttribute($id);
        return redirect()->back()->with('success_message', $message);
    }

    public function updateImageSorting(Request $request) {
        $this->productService->updateImageSorting($request->sorted_images);
        return response()->json(['status' => 'success']);
    }

    public function deleteDropzoneImage(Request $request){
        $deleted = $this->productService->deleteDropzoneImage($request->image);
        return response()->json(['status' => $deleted ? 'deleted' : 'file_not_found'], $deleted ? 200 : 404);
    }

    public function deleteTempProductImage(Request $request){
        $deleted = $this->productService->deleteDropzoneImage($request->filename);
        return response()->json(['status' => $deleted ? 'deleted' : 'file_not_found'], $deleted ? 200 : 404);
    }

    public function deleteTempProductVideo(Request $request){
        $deleted = $this->productService->deleteDropzoneVideo($request->filename);
        return response()->json(['status' => $deleted ? 'deleted' : 'file_not_found'], $deleted ? 200 : 404);
    }


}
