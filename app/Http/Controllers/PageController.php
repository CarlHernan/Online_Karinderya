<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\OrderController;


//Page controller v1 -perez
class PageController extends Controller
{
    public function home()
    {
        $featured = Product::where('is_featured', true)->take(3)->get();
        return view('home', compact('featured'));
    }

    public function about()
    {
        return view('about');
    }

    public function menu(Request $request)
    {
        $categories = Category::withCount('products')->get();
        $selectedCategory = $request->get('category');
        $search = $request->get('search');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        
        $query = Product::with('category');
        
        // Filter by category
        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory);
        }
        
        // Search by name or description
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('dish_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by price range
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        
        $menu = $query->paginate(12);
        
        return view('menu', compact('menu', 'categories', 'selectedCategory', 'search', 'minPrice', 'maxPrice'));
    }

    public function orders(Request $request)
    {
        $orderController = new OrderController();
        return $orderController->customerOrders($request);
    }
}
