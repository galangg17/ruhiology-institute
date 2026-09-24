<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_order_and_deduct_stock(): void
    {
        $cat = ProductCategory::create(['name' => 'Buku', 'slug' => 'buku']);
        $product = Product::create([
            'category_id' => $cat->id,
            'title' => 'Buku RQ',
            'slug' => 'buku-rq',
            'author' => 'Prof. Iskandar',
            'description' => 'Deskripsi',
            'price' => 100000,
            'stock' => 10,
            'status' => 'published',
        ]);

        $orderService = app(OrderService::class);
        $order = $orderService->createOrder([
            'customer_name' => 'Doni',
            'customer_email' => 'doni@test.com',
            'customer_phone' => '081299887766',
            'shipping_address' => 'Jl. Jambi No. 1',
        ], [
            ['product_id' => $product->id, 'quantity' => 2]
        ]);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'customer_name' => 'Doni']);
        $this->assertEquals(8, $product->fresh()->stock);
    }
}
