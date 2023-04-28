<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use WithFaker;
    /**
     * A feature test_create_transaction.
     *
     * @return void
     */
    public function test_create_transaction()
    {
        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $product = Product::create([
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(5000, 200000),
        ]);

        $product2 = Product::create([
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(5000, 200000),
        ]);

        $this->json('POST', 'api/transaction',
            [
                "user_id" => $user->id,
                "products" => [
                    [
                        "id" => $product->id,
                        "qty" => $this->faker->numberBetween(1, 30),
                    ],
                    [
                        "id" => $product2->id,
                        "qty" => $this->faker->numberBetween(1, 30),
                    ],
                ],
                "bank" => 'bca',
            ], ['Accept' => 'application/json'])
        ->assertStatus(201)
        ->assertJsonStructure([
            "success",
            "message",
            "data"
        ]);
    }
}
