<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::with(['category', 'primaryImage', 'raffle'])
        ->latest()
        ->paginate(12);

        return view('products.index', compact('products'));
    }
}
