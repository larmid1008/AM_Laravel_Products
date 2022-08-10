<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected array $fakeRequestData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::first();

        $this->categories = Category::take(2)->get();

        $this->fakeData = [
            "name" => $this->faker->word(),
            "price" => $this->faker->random_int(1,1000),
            "published" => $this->faker->boolean(),
            "categories" => [$this->categories[0]->id, $this->categories[1]->id]
        ];
    }

    function testProductIndex()
    {
        $response = $this->getJson("/api/products");
        $this->assertEquals(200, $response->getStatusCode());
    }

    function testProductStore()
    {
        $response = $this->postJson("/api/products", $this->fakeRequestData);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertInstanceOf(Product::class, $response->getOriginalContent());
    }
    function testProductUpdate()
    {
        $response = $this->patchJson("/api/products/{$this->product->id}", $this->fakeRequestData);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(Product::class, $response->getOriginalContent());
    }
    function testProductDestroy()
    {
        $response = $this->deleteJson("/api/products/{$this->product->id}", $this->fakeRequestData);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(Product::class, $response->getOriginalContent());
    }

}
