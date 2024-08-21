<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use PHPUnit\Framework\Attributes\Test;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_product()
    {
        $response = $this->post('/api/products', [
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
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', [
            'code' => '1',
            'product_name' => 'Product Name'
        ]);
    }

    #[Test]
    public function it_can_update_a_product()
    {
        $product = Product::factory()->create([
            'code' => '1',
            'product_name' => 'Product Name',
            'status' => 'published',
        ]);

        $response = $this->put('/api/products/' . $product->code, [
            'code' => '2',
            'product_name' => 'Updated Product Name',
            'status' => 'draft',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'code' => '2',
            'product_name' => 'Updated Product Name',
        ]);
    }

    #[Test]
    public function it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->delete('/api/products/' . $product->code);

        $response->assertStatus(204);
        
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => 'trash'
        ]);
    }
}
