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

    public function store(array $data): Category
    {
        return Category::create($data);
    }

    public function show(int $id): Category
    {
        return Category::findOrFail($id);
    }
}
