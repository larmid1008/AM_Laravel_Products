<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected array $fakeRequestData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::first();

        $this->fakeData = [
            "name" => $this->faker->word(),
        ];
    }

    function testCategoryIndex()
    {
        $response = $this->getJson("/api/categories");
        $this->assertEquals(200, $response->getStatusCode());
    }

    function testCategoryStore()
    {
        $response = $this->postJson("/api/categories", $this->fakeRequestData);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertInstanceOf(Category::class, $response->getOriginalContent());
    }

    function testCategoryDestroy()
    {
        $response = $this->deleteJson("/api/categories/{$this->category->id}", $this->fakeRequestData);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(Category::class, $response->getOriginalContent());
    }
}
