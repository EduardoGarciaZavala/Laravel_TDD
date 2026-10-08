<?php

namespace App\Http\Controllers\Api\V1\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Category\StoreCategoryRequest;
use App\Http\Resources\Api\V1\Category\CategoryResource;
use App\Interfaces\Services\CategoryServiceInterface;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryServiceInterface $category
    ) {}

    public function index()
    {
        return response()->json(['categories' => CategoryResource::collection($this->category->index())]);
    }

    public function store(StoreCategoryRequest $request)
    {

        $data = $request->validated();

        return response()->json(['category' => new CategoryResource($this->category->store($data))], 201);
    }

    public function show(int $id)
    {
        return response()->json(['category' => new CategoryResource($this->category->show($id))]);
    }
}
