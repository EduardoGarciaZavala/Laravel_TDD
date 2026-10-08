<?php

namespace App\Interfaces\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;


interface CategoryRepositoryInterface
{
    public function index(): Collection;

    public function store(array $data): Category;

    public function show(int $id): Category;
}
