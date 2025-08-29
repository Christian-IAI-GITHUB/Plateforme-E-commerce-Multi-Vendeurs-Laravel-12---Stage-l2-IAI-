<?php
namespace App\Services\Front;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Category;
class ProductService
{
    // Add methods related to product services here
    public function getCategoryListingData($url)
    {
        $categoryInfo = Category::categoryDetails($url);

            $query = Product::with(['product_images'])
            ->whereIn('category_id', $categoryInfo['catIds'])
            ->where('status', 1);
            $query = $this->applyFilters($query);
            $products = $query->paginate(12)->withQueryString();

            return [
                'categoryDetails' => $categoryInfo['categoryDetails'],
                'categoryProducts' => $products,
                'breadcrumbs' => $categoryInfo['breadcrumbs'],
                'selectedSort' => request()->get('sort', ''),
                'url' => $url
        ];
    }

    private function applyFilters($query)
    {
        $sort = request()->get('sort');
        switch($sort){
            case 'latest':
                $query->orderBy('created_at','desc');
                break;
            case 'low_to_high':
                $query->orderBy('final_price', 'ASC');
                break;
            case 'high_to_low':
                $query->orderBy('final_price', 'DESC');
                break;
            case 'best_selling':
                $query->inRandomOrder();
                break;
            case 'featured':
                $query->where('is_featured', 'Yes')->orderBy('created_at', 'DESC');
                break;
            case 'discounted':
                $query->where('product_discount', '>',0);
                break;
            default:
                $query->orderBy('created_at', 'DESC');
        }
        return $query;
    }
}

?>