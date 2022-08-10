<?php

namespace App\Http\Controllers;

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

    public function store(Request $request): JsonResponse
    {
        return response()->json(Category::create(['name' => $request->name]), Response::HTTP_CREATED);
    }

    public function delete(int $categoryId): JsonResponse
    {
        $category = Category::findOrFail($categoryId);

        if (!$category->products->isEmpty) {
            // Throw Not Empty Category Exception
        }

        $category->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
