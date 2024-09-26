<?php

namespace App\Repositories\Plan;

use App\Models\Price;

interface PriceRepositoryInterface
{
    public function getActiveById(int $priceId): ?Price;
}
