<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActionControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testMakeOrder()
    {
        Mail::fake();

        $payload = [
            'customer_name' => 'Inioluwa Alake',
            'product_id' => 1,
            'quantity' => 100,
        ];

        $response = $this->post('/api/place/order', $payload);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Order placed successfully']);

        // Assert the order was stored correctly
        $order = Order::first();
        $this->assertEquals('Inioluwa Alake', $order->customer_name);
        $this->assertEquals(1, $order->product_id);
        $this->assertEquals(100, $order->quantity);

        // Assert the stock was correctly updated
        $beefStock = Ingredient::find(1)->stock_Kg;
        $cheeseStock = Ingredient::find(2)->stock_Kg;
        $onionStock = Ingredient::find(3)->stock_Kg;

      
        $this->assertEqualsWithDelta(5.0, $beefStock, 0.01);
        $this->assertEquals(2.0, $cheeseStock);
        $this->assertEquals(-1.0, $onionStock);
    }
}