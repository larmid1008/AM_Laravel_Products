<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $request): JsonResponse
    {
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'published' => $request->published
        ]);

        $product->categories()->attach($request->categories);

        return response()->json($product, Response::HTTP_CREATED);
    }

    public function update(int $productId, Request $request): Product
    {
        $product = Product::findOrFail($productId);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'published' => $request->published
        ]);

        return $product;
    }

    public function delete(int $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);
        $product->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
