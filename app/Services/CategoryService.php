<?php

namespace App\Services;

use App\Interfaces\Repositories\CategoryRepositoryInterface;
use App\Interfaces\Services\CategoryServiceInterface;
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
}
