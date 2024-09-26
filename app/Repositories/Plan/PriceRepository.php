<?php

namespace App\Repositories\Plan;

use App\Models\Price;

class PriceRepository implements PriceRepositoryInterface
{
    public function __construct(protected Price $model)
    {
    }

    public function getActiveById(int $priceId): ?Price
    {
        return $this->model->active()->where('id', $priceId)->first();
    }
}
