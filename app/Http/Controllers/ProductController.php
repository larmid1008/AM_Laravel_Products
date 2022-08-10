<?php

namespace App\Http\Controllers;

use App\Filters\Product\ProductCategoryFilter;
use App\Filters\Product\ProductNameFilter;
use App\Filters\Product\ProductNotDeletedFilter;
use App\Filters\Product\ProductPriceFilter;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductController extends Controller
{
    public function index()
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::custom('name', new ProductNameFilter()),
                AllowedFilter::custom('category', new ProductCategoryFilter() ),
                AllowedFilter::custom('price', new ProductPriceFilter()),
                AllowedFilter::custom('notDeleted', new ProductNotDeletedFilter()), // These are requirements but I would do 'withDeleted', because, in most situations, products without deleted status will be needed and not vice versa
                AllowedFilter::exact('published'),
            ])->withTrashed()->get();
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
