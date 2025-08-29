<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class VendorController extends Controller
{
    public function dashboard()
    {
        $vendor = Auth::user();
        
        // Statistiques du vendeur
        $totalProducts = Product::where('user_id', $vendor->id)->count();
        $activeProducts = Product::where('user_id', $vendor->id)->where('status', 1)->count();
        $inactiveProducts = Product::where('user_id', $vendor->id)->where('status', 0)->count();
        $featuredProducts = Product::where('user_id', $vendor->id)->where('is_featured', 'Yes')->count();
        
        // Produits récents
        $recentProducts = Product::where('user_id', $vendor->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
        
        return view('vendor.dashboard', compact(
            'vendor',
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'featuredProducts',
            'recentProducts'
        ));
    }
}