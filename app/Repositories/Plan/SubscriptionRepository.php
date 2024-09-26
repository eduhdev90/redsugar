<?php

namespace App\Repositories\Plan;

use App\Models\Subscription;
use App\ValueObjects\Plan\SubscriptionStatus;
use Illuminate\Support\Collection;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function __construct(protected Subscription $model)
    {
    }

    public function getActiveAll(): Collection
    {
        return $this->model->active()->get();
    }

    public function getActiveByUserProfileId(int $userProfileId): ?Subscription
    {
        return $this->model->where('user_profile_id', $userProfileId)->active()->with('price.product')->first();
    }

    public function getByExternalId(string $externalId): ?Subscription
    {
        return $this->model->where('external_id', $externalId)->first();
    }

    public function create(array $data): Subscription
    {
        return $this->model::create($data);
    }

    public function update(int $id, array $data): Subscription
    {
        $subscription = $this->model::where('id', '=', $id)->firstOrFail();
        $subscription->update($data);

        return $subscription;
    }

    public function cancel(int $id): Subscription
    {
        $subscription = $this->model::where('id', '=', $id)->firstOrFail();
        $subscription->update(['status' => SubscriptionStatus::CANCELED->value]);

        return $subscription;
    }
}
