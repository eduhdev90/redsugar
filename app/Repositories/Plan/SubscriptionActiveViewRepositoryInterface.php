<?php

namespace App\Repositories\Plan;

use App\Models\Views\SubscriptionActiveView;

interface SubscriptionActiveViewRepositoryInterface
{
    public function getByBenefitName(int $userProfileId, string $benefitName): ?SubscriptionActiveView;
}
