<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\IndexService;
use Illuminate\Http\Request;



class IndexController extends Controller
{
    protected $indexService;
    public function __construct(IndexService $indexService)
    {
        $this->indexService = $indexService;
    }
    
    public function index(){
    $featured = $this->indexService->featuredProducts();
    $newArrival = $this->indexService->newArrivalProducts();
    $categories = $this->indexService->homeCategories();
        return view('front.index')
        ->with($featured)
        ->with($newArrival)
        ->with($categories);
    }

}
