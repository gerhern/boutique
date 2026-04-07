<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\{ProductStoreRequest, ProductUpdateRequest};
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'primaryImage', 'raffle'])
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    public function create(Request $request)
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductStoreRequest $request)
    {
        try {
            $uploadedFiles = [];

            return DB::transaction(function () use ($request, &$uploadedFiles) {
                $product = Product::create([
                    'name'          => $request->name,
                    'description'   => $request->description ?? '',
                    'status'        => $request->status,
                    'price'         => $request->price,
                    'category_id'   => $request->category_id
                ]);

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $index => $file) {
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
        } catch (\Exception $e) {
            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            Log::error('Error saving product: ' . $e->getMessage());
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
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $uploadedFiles = [];
        $filesToDelete = [];

        try {
            DB::transaction(function () use ($request, $product, &$uploadedFiles, &$filesToDelete) {
                $product->update([
                    'name'          => $request->name ?? $product->name,
                    'description'   => $request->description ?? $product->description,
                    'status'        => $request->status ?? $product->status,
                    'price'         => $request->price ?? $product->price,
                    'category_id'   => $request->category_id ?? $product->category_id
                ]);

                if ($request->filled('delete_images')) {
                    $images = $product->images()->whereIn('id', $request->delete_images)->get();

                    foreach ($images as $image) {
                        $filesToDelete[] = $image->path;
                        $image->delete();
                    }
                }

                if ($request->hasFile('images')) {
                    $currentCount = $product->images()->count();
                    $availableSlots = 3 - $currentCount;

                    foreach ($request->file('images') as $index => $file) {
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
                }

                if (!$product->images()->where('is_primary', true)->exists()) {
                    $product->images()->first()?->update(['is_primary' => true]);
                }
            });

            foreach ($filesToDelete as $path) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->route('admin.products.show', $product)
                ->with('status', 'Product updated successfully.');

        } catch (\Exception $e) {
            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
