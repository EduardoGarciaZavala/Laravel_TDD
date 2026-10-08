<?php

namespace App\Services;

use App\Interfaces\Repositories\CategoryRepositoryInterface;
use App\Interfaces\Services\CategoryServiceInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $category
    ) {}
    public function index(): Collection
    {
        return $this->category->index();
    }

    public function store(array $data): Category
    {

        $data['image'] = $data['image']->store('categories', 'public');
        return $this->category->store($data);
    }

    public function show(int $id): Category
    {
        return $this->category->show($id);
    }
}
