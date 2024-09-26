<?php

namespace App\Repositories;

use App\Models\ProfileView;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProfileViewRepository implements ProfileViewRepositoryInterface
{
    public function __construct(protected ProfileView $model)
    {
    }

    public function getVisitedTodayByVisitorId(int $visitor_id, int $viewable_id): ?ProfileView
    {
        return $this->model->where('visitor_id', $visitor_id)->where('viewable_id', $viewable_id)->whereDate('created_at', Carbon::today())->first();
    }


    public function getVisitedYesterday(): Collection
    {
        return DB::table('profile_views')->select('viewable_id', DB::raw('count(*) as total'))->whereDate('created_at', Carbon::yesterday())->groupBy('viewable_id')->get();
    }
}
