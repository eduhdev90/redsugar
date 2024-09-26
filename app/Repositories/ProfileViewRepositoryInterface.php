<?php

namespace App\Repositories;

use App\Models\ProfileView;
use Illuminate\Support\Collection;

interface ProfileViewRepositoryInterface
{
    public function getVisitedTodayByVisitorId(int $visitor_id, int $viewable_id): ?ProfileView;
    public function getVisitedYesterday(): Collection;
}
