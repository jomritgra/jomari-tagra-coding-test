<?php

namespace Tests\Feature;

use App\Models\Products;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateProduct()
    {
        $data = [
            'product_name' => 'Test Product',
            'product_description' => 'This is a test product',
            'product_price' => 9.99
        ];

        $response = $this->post('/api/products', $data);

        $response->assertStatus(200)
            ->assertJson([
                'product_name' => $data['product_name'],
                'product_description' => $data['product_description'],
                'product_price' => $data['product_price']
            ]);
    }

    public function testUpdateProduct()
    {
        $product = Products::factory()->create();

        $data = [
            'product_name' => 'Updated Product',
            'product_description' => 'This is an updated product',
            'product_price' => 12.99
        ];

        $response = $this->put('/api/products/' . $product->id, $data);

        $response->assertStatus(200)
            ->assertJson([
                'product_name' => $data['product_name'],
                'product_description' => $data['product_description'],
                'product_price' => $data['product_price']
            ]);
    }

    public function testDeleteProduct()
    {
        $product = Products::factory()->create();

        $response = $this->delete('/api/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product deleted successfully'
            ]);
    }

    public function testShowProduct()
    {
        Products::factory()->count(10)->create();

        $response = $this->get('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'product_name',
                        'product_description',
                        'product_price',
                        'created_at',
                        'updated_at'
                    ]
                ],
            ]);
    }
}
