<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;


class CategoryRepository implements CategoryRepositoryInterface
{
    public function index(): Collection
    {
        return Category::all();
    }
}
