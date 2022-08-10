<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CategoryController extends Controller
{

    public function index()
    {
        return Category::all();
    }

    /**
     * @param Request $request
     * @return CategoryResource
     */
    public function store(Request $request): CategoryResource
    {
        $category = Category::create(['name' => $request->name]);
        return CategoryResource::make($category);
    }

    /**
     * @param int $categoryId
     * @return CategoryResource
     */
    public function destroy(int $categoryId): CategoryResource
    {
        $category = Category::findOrFail($categoryId);

        if (!$category->products->isEmpty()) {
            // Throw Not Empty Category Exception
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Category attached to products");
        }

        $category->delete();

        return CategoryResource::make($category);
    }

}
