<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        if (!$category->products->isEmpty) {
            // Throw Not Empty Category Exception
        }

        $category->delete();

        return CategoryResource::make($category);
    }

}
