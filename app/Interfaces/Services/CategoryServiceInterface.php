<?php

namespace App\Interfaces\Services;

use Illuminate\Database\Eloquent\Collection;

interface CategoryServiceInterface
{
    public function index(): Collection;
}
