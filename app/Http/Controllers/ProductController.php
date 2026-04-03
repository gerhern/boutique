<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\ProductStoreRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::with(['category', 'primaryImage', 'raffle'])
        ->latest()
        ->paginate(12);

        return view('products.index', compact('products'));
    }

    public function create(Request $request){
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductStoreRequest $request){
        try{
            $uploadedFiles = [];

            return DB::transaction(function () use ($request, &$uploadedFiles) {
                $product = Product::create([
                    'name'          => $request->name,
                    'description'   => $request->description ?? '',
                    'status'        => $request->status,
                    'price'         => $request->price,
                    'category_id'   => $request->category_id
                ]);

                if($request->hasFile('images')){
                    foreach($request->file('images') as $index => $file){
                        $path = $file->store('products', 'public');
                        $product->images()->create([
                            'path'       => $path,
                            'is_primary' => $index === 0,
                            'sort_order' => $index
                        ]);
                        $uploadedFiles[] = $path;
                    }
                }
                return redirect(route('admin.products.show', $product));
            });

        }catch(\Exception $e){
            foreach($uploadedFiles as $file){
                Storage::disk('public')->delete($file);
            }

            Log::error('Error saving product: ' . $e->getMessage());
            return back()->withInput()
                     ->withErrors(['error' => 'Product can not be stored, try again later.']);
        }
    }

    public function adminShow(Request $request, Product $product){

    }
}
