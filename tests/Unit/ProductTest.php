<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepository;
use PHPUnit\Framework\Attributes\Test;
use App\Services\ProductService;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = new ProductService($this->app->make(ProductRepository::class));
    }

    #[Test]
    public function test_it_can_create_a_product()
    {
        $productData = [
            'code' => '1',
            'status' => 'published',
            'imported_t' => now(),
            'url' => 'https://example.com',
            'creator' => 'John Doe',
            'created_t' => now(),
            'last_modified_t' => now(),
            'product_name' => 'Product Name',
            'quantity' => '100',
            'brands' => 'Brand X',
            'categories' => 'Category Y',
            'labels' => 'Label Z',
            'cities' => 'City A',
            'purchase_places' => 'Place B',
            'stores' => 'Store C',
            'ingredients_text' => 'Ingredients',
            'traces' => 'Traces',
            'serving_size' => 'Size',
            'serving_quantity' => '1',
            'nutriscore_score' => '80',
            'nutriscore_grade' => 'A',
            'main_category' => 'Main Category',
            'image_url' => 'https://example.com/image.jpg'
        ];

        $product = $this->productService->createProduct($productData);

        $this->assertEquals('1', $product->code);
        $this->assertDatabaseHas('products', [
            'code' => '1',
            'product_name' => 'Product Name'
        ]);
    }

    #[Test]
    public function test_it_can_read_a_product()
    {
        $product = Product::factory()->create([
            'code' => '1',
            'product_name' => 'Read Test Product'
        ]);

        $retrievedProduct = $this->productService->getProductByCode($product->code);

        $this->assertEquals('1', $retrievedProduct->code);
        $this->assertEquals('Read Test Product', $retrievedProduct->product_name);
    }

    #[Test]
    public function test_it_can_update_a_product()
    {
        $product = Product::factory()->create([
            'code' => '1',
            'product_name' => 'Initial Product Name'
        ]);

        $updateData = [
            'code' => '2',
            'product_name' => 'Updated Product Name'
        ];

        $updatedProduct = $this->productService->updateProduct($product->code, $updateData);

        $this->assertEquals('2', $updatedProduct->code);
        $this->assertEquals('Updated Product Name', $updatedProduct->product_name);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'code' => '2',
        ]);
    }

    #[Test]
    public function test_it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $this->productService->deleteProduct($product->code);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => 'trash'
        ]);
    }
}
