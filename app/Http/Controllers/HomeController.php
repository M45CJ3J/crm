<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

use App\Models\Product;
use App\Models\Category;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      
        if(auth()->user()->password == '$2y$10$uFSiN9VaOexI.Lb0evc3s.zzGzlLbY5v5p30gemw2ajynXhmu07rK'){
            return redirect()->route('password.request');
        }else{

            // Fetch product data
        $products = Product::all();
        $productData = [
            'labels' => $products->pluck('name'),
            'data' => $products->pluck('quantity') // Ensure you have a 'quantity' column
        ];

        // Fetch category data
        $categories = Category::withCount('products')->get();
        $categoryData = [
            'labels' => $categories->pluck('name'),
            'data' => $categories->pluck('products_count')
        ];

        return view('home', [
            'productData' => $productData,
            'categoryData' => $categoryData
        ]);

        }
       // return view('home');
    }
}
