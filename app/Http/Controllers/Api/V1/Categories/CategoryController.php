<?php

namespace App\Http\Controllers\Api\V1\Categories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Category\CategoryResource;
use App\Interfaces\Services\CategoryServiceInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryServiceInterface $category
    ) {}
    
    public function index()
    {
        return response()->json(['categories' => CategoryResource::collection($this->category->index())]);
    }
}
