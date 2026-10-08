<?php

namespace App\Interfaces\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryServiceInterface
{
    public function index(): Collection;

    public function store(array $data): Category;

    public function show(int $id): Category;
}
