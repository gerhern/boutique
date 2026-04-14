<?php

namespace Tests\Feature;

use App\Models\ProductImage;
use App\traits\SetTestingData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImagesTest extends TestCase
{
    use SetTestingData;
    public function test_image_file_is_deleted_when_model_is_deleted(): void
    {
        Storage::fake('public');
        $product = $this->createProduct();
        $image = ProductImage::factory()->create(['path' => 'products/test.jpg', 'product_id' => $product->id]);

        Storage::disk('public')->put('products/test.jpg', 'content');

        $image->delete();

        Storage::disk('public')->assertMissing('products/test.jpg');
    }
}
