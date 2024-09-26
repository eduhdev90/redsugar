<?php

namespace App\Repositories\Plan;

use App\Models\Subscription;
use Illuminate\Support\Collection;

interface SubscriptionRepositoryInterface
{
    public function getActiveAll(): Collection;
    public function getActiveByUserProfileId(int $userProfileId): ?Subscription;
    public function getByExternalId(string $externalId): ?Subscription;
    public function create(array $data): Subscription;
    public function update(int $id, array $data): Subscription;
    public function cancel(int $id): Subscription;
}
