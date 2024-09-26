<?php

namespace App\Repositories\Plan;

use App\Models\Product;
use App\ValueObjects\Plan\ProductType;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(protected Product $model)
    {
    }

    public function getActiveAll(): Collection
    {
        return $this->model->active()->with('prices', function ($query) {
            $query->active();
        })->get();
    }

    public function getActiveById(int $productId): ?Product
    {
        return $this->model->active()->where('id', $productId)->first();
    }

    public function getFreeProductByProfile(int $profileId): ?Product
    {
        return $this->model
            ->where('profile', $profileId)
            ->where('type_plan', ProductType::FREE->value)
            ->active()
            ->first();
    }
}
