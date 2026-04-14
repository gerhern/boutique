<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{

    public function createProduct(array $data, bool $hasFiles = false)
    {
        try {
            $uploadedFiles = [];

            $product = DB::transaction(function () use ($data, $hasFiles, &$uploadedFiles) {
                $product = Product::create([
                    'uuid'          => Str::uuid(),
                    'name'          => $data['name'],
                    'description'   => $data['description'],
                    'status'        => $data['status'],
                    'price'         => $data['price'],
                    'category_id'   => $data['category_id']
                ]);

                if ($hasFiles) {
                    $uploadedFiles = $this->uploadProductImages($product, $data['images']);
                }

                return $product;
            });


            return [true, $product];
        } catch (\Exception $e) {
            Log::error('Error saving product: ' . $e->getMessage());
            $this->deleteImagesOnDisk($uploadedFiles);
            return [false, null];
        }
    }

    public function updateProduct(array $data, Product $product, bool $hasFilesToDelete = false, bool $hasImagesToUpdate = false)
    {
        $uploadedFiles = [];

        try {
            DB::transaction(function () use ($data, $product, &$hasImagesToUpdate, &$uploadedFiles, &$hasFilesToDelete) {
                $product->update([
                    'name'          => $data['name'] ?? $product->name,
                    'description'   => $data['description'] ?? $product->description,
                    'status'        => $data['status'] ?? $product->status,
                    'category_id'   => $data['category_id'] ?? $product->category_id
                ]);

                if ($hasFilesToDelete) {
                    $this->deleteImagesOnBase($product, $data['delete_images']);
                }

                if ($hasImagesToUpdate) {
                    $uploadedFiles = $this->handleNewImages($product, $data['images']);
                }

                if (!$product->images()->where('is_primary', true)->exists()) {
                    $product->images()->first()?->update(['is_primary' => true]);
                }
            });

            return [true, 'Product updated successfully'];
        } catch (\Exception $e) {

            Log::error('Error updating product: ' . $e->getMessage());
            $this->deleteImagesOnDisk($uploadedFiles);

            return [false, $e->getMessage()];
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
