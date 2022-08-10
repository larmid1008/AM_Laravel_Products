<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }


    private function checkValidCategories($categories) {
        foreach ($categories as $_ => $categoryId) {
            $category = Category::find($categoryId);
            if ($category == null) {
                throw new HttpException(Response::HTTP_BAD_REQUEST, "Category with id ".$categoryId. " not exist");
            }
        }
    }

    private function checkAmountCategories($categories) {
        $count = collect($categories)->count();

        if ($count < 2 || $count > 10)
        {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Number of categories is limited from 2 to 10");
        }
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->checkAmountCategories($request->categories);

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'published' => $request->published
        ]);

        $this->checkValidCategories($request->categories);
        $product->categories()->attach($request->categories);

        return ProductResource::make($product);
    }

    /**
     * @param int $productId
     * @param Request $request
     * @return ProductResource
     */
    public function update(int $productId, Request $request): ProductResource
    {
        $this->checkAmountCategories($request->categories);

        $product = Product::findOrFail($productId);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'published' => $request->published
        ]);

        $this->checkValidCategories($request->categories);
        $product->categories()->sync($request->categories);

        return ProductResource::make($product);
    }

    /**
     * @param int $productId
     * @return ProductResource
     */
    public function destroy(int $productId): ProductResource
    {
        $product = Product::findOrFail($productId);
        $product->delete();

        return ProductResource::make($product);
    }
}
