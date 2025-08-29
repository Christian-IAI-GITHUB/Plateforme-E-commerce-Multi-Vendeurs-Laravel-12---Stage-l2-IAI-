<?php
namespace App\Services\Front;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Category;

class IndexService
{
    //les banners



    //produits vedettes
    public function featuredProducts(){
        $featuredProducts = Product::select('id', 'category_id','product_name', 'discount_applied_on', 
        'product_price', 'product_discount','final_price','group_code','main_image')
        ->with(['product_images'])
        ->where(['is_featured'=>'Yes', 'status'=>'1'])
        ->where('stock', '>', 0)
        ->inRandomOrder()
        ->limit(8)
        ->get()
        ->toArray();
    return compact('featuredProducts');
    }
    //new arrival products
    public function newArrivalProducts(){
        $newArrivalProducts = Product::select('id', 'category_id','product_name', 'discount_applied_on', 
        'product_price', 'product_discount','final_price','group_code','main_image')
        ->with(['product_images'])
        ->where('status',1)
        ->where('stock', '>', 0)
        ->latest()
        ->orderBy('id', 'DESC')
        ->limit(8)
        ->get()
        ->toArray();
    return compact('newArrivalProducts');
    }

    public function homeCategories(){

        $categories = Category::select('id', 'name', 'image','url')
        ->whereNull('parent_id')
        ->where('status', 1)
        ->where('menu_status', 1)
        ->get()
        ->map(function($category) {
            $allCategoryIds = $this->getAllCategoryIds($category ->id);
            $productCount = Product::whereIn('category_id', $allCategoryIds)
                ->where('status', 1)
                ->where('stock', '>', 0)
                ->count();
        return [
                'id' => $category->id,
                'name' => $category->name,
                'image' => $category->image,
                'url' => $category->url,
                'product_count' => $productCount
            ];
        });
        return ['categories'=>$categories->toArray()];
}
private function getAllCategoryIds($parentId)
{
    $categoryIds = [$parentId];
    $childIds = Category::where('parent_id',$parentId)
    ->where('status', 1)
    ->pluck('id');
    foreach($childIds as $childId) {
        $categoryIds = array_merge($categoryIds, $this->getAllCategoryIds($childId));
    }
    return $categoryIds;
}
}
?>