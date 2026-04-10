<?php

namespace App\Http\Controllers;

use App\enums\ProductStatus;
use App\Http\Requests\admin\{ProductStoreRequest, ProductUpdateRequest};
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
                    $uploadedFiles = $this->uploadProductImages($product, $request->file('images'));
                }

                return redirect(route('admin.products.show', $product))
                    ->with('success', 'Product created successfully');
            });
        } catch (\Exception $e) {
            Log::error('Error saving product: ' . $e->getMessage());
            $this->deleteImagesOnDisk($uploadedFiles);

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

    public function update(ProductUpdateRequest $request, Product $product)
    {
        if (Gate::denies('canBeUpdated', $product)) {
            return back()
                ->withErrors(['status' => "{$product->status->value} products cannot be edited"]);
        }

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
                    $filesToDelete = $this->deleteImagesOnBase($product, $request->delete_images);
                }

                if ($request->hasFile('images')) {
                    $uploadedFiles = $this->handleNewImages($product, $request->file('images'));
                }

                if (!$product->images()->where('is_primary', true)->exists()) {
                    $product->images()->first()?->update(['is_primary' => true]);
                }
            });

            $this->deleteImagesOnDisk($filesToDelete);

            return redirect()
                ->route('admin.products.show', $product)
                ->with('status', 'Product updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            $this->deleteImagesOnDisk($uploadedFiles);

            return back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function uploadProductImages(Product $product, array $files): array
    {
        $paths = [];
        foreach ($files as $index => $file) {
            $path = $file->store('products', 'public');
            $paths[] = $path;

            $product->images()->create([
                'path'       => $path,
                'is_primary' => $index === 0,
                'sort_order' => $index
            ]);
        }
        return $paths;
    }

    private function deleteImagesOnDisk(array $files): void
    {
        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }
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
