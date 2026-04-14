<?php

namespace App\Http\Controllers;

use App\enums\ProductStatus;
use App\Http\Requests\admin\{ProductStoreRequest, ProductUpdateRequest};
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'primaryImage', 'raffle'])
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    public function adminIndex(Request $request)
    {
        $products = Product::latest()->paginate(12);

        return view('admin.products.index', compact('products'));
    }

    public function create(Request $request)
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductStoreRequest $request, ProductService $productService)
    {
        [$wasSaved, $product] = $productService
            ->createProduct($request->validated(), $request->hasFile('images'));

        if($wasSaved){
            return redirect(route('admin.products.show', $product))
                ->with('success', 'Product created successfully');
        }else{
            return back()->withInput()
                ->withErrors(['error' => 'Product can not be stored, try again later.']);
        }

    }

    public function adminShow(Request $request, Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Request $request, Product $product)
    {
        $categories = Category::all();
        $productStatus = ProductStatus::options();
        return view('admin.products.edit', compact('product', 'categories', 'productStatus'));
    }

    public function update(ProductUpdateRequest $request, Product $product, ProductService $productService)
    {
        [$wasUpdated, $message] = $productService
            ->updateProduct(
                $request->validated(),
                $product,
                $request->filled('delete_images'),
                $request->hasFile('images')
            );

            return $wasUpdated
                ? redirect()
                    ->route('admin.products.show', $product)
                    ->with('success', $message)
                : back()
                    ->withErrors(['error' => $message]);
    }




    private function deleteImagesOnBase(Product $product, array $imagesToDelete): array
    {
        $images = $product->images()->whereIn('id', $imagesToDelete)->get();

        foreach ($images as $image) {
            $image->delete();
        }
        return $images->pluck('path')->toArray();
    }

    private function handleNewImages(Product $product, array $images): array
    {
        $uploadedFiles = [];
        $currentCount = $product->images()->count();
        $availableSlots = config('boutique.max_images_per_product') - $currentCount;
        foreach ($images as $index => $file) {
            if ($index < $availableSlots) {
                $path = $file->store('products', 'public');
                $uploadedFiles[] = $path;

                $product->images()->create([
                    'path' => $path,
                    'is_primary' => ($currentCount === 0 && $index === 0),
                    'sort_order' => $currentCount + $index
                ]);
            }
        }

        return $uploadedFiles;
    }
}
