<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;

class WebController extends Controller
{
    function index()
    {
        $categories = Category::orderBy('position', 'asc')->get();

        return view('categories.index')->with(compact('categories'));
    }

    function products($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->orderBy('position')->get();
        $category = Category::find($categoryId);

        return view('products.index')->with(compact('products', 'category'));
    }

    function services($categoryId)
    {
        $services = Service::where('category_id', $categoryId)->orderBy('position')->get();
        $category = Category::find($categoryId);

        return view('services.index')->with(compact('services', 'category'));
    }
}
