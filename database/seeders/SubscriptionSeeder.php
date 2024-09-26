<?php

namespace Database\Seeders;

use App\Models\UserProfile;
use App\Services\Plan\SubscriptionService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function __construct(protected SubscriptionService $subscriptionService)
    {
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = UserProfile::all();
        foreach ($users as $user) {
            $this->subscriptionService->storeFreePlan($user->id);
        }
    }
}
