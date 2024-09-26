<?php

namespace App\Repositories\Plan;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function getActiveAll(): Collection;
    public function getActiveById(int $productId): ?Product;
    public function getFreeProductByProfile(int $profileId): ?Product;
}
