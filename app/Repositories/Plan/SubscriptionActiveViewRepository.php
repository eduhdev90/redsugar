<?php

namespace App\Repositories\Plan;

use App\Models\Views\SubscriptionActiveView;

class SubscriptionActiveViewRepository implements SubscriptionActiveViewRepositoryInterface
{
    public function __construct(protected SubscriptionActiveView $model)
    {
    }

    public function getByBenefitName(int $userProfileId, string $benefitName): ?SubscriptionActiveView
    {
        return $this->model->where('user_profile_id', $userProfileId)->where('benefit', $benefitName)->first();
    }
}
