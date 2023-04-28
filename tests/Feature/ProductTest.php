<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use WithFaker;
    /**
     * A feature test_the_product.
     *
     * @return void
     */
    public function test_the_product()
    {
        $this->json('GET', 'api/product', [], ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure([
            "success",
            "message",
            "data",
        ]);
    }


     /**
     * A feature test_create_product_success.
     *
     * @return void
     */
    public function test_create_product_success()
    {
        $this->json('POST', 'api/product', [
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(5000, 200000),
        ], ['Accept' => 'application/json'])
        ->assertStatus(201)
        ->assertJsonStructure([
            "success",
            "message",
        ]);
    }

     /**
     * A feature test_detail_product_success.
     *
     * @return void
     */
    public function test_detail_product_success()
    {
        $product = Product::create([
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(5000, 200000),
        ]);
        $this->json('GET', 'api/product/'.$product->id, [], ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure([
            "success",
            "message",
            "data",
        ]);
    }

     /**
     * A feature test_update_product_success.
     *
     * @return void
     */
    public function test_update_product_success()
    {
        $product = Product::create([
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(5000, 200000),
        ]);
        $this->json('PUT', 'api/product/'.$product->id, [
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(5000, 200000),
        ], ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure([
            "success",
            "message",
        ]);
    }

    /**
     * A feature test_delete_product_success.
     *
     * @return void
     */
    public function test_delete_product_success()
    {
        $product = Product::create([
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(5000, 200000),
        ]);
        $this->json('DELETE', 'api/product/'.$product->id, [], ['Accept' => 'application/json'])
        ->assertStatus(204);
    }
}
