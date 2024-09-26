<?php

namespace App\Repositories;

use App\Models\Favorite;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    public function __construct(protected Favorite $model)
    {
    }

    public function getByFavoritedId(int $userId, int $favoritedId): null|Favorite
    {
        return $this->model::where('user_profile_id', $userId)
            ->where('favorited_id', $favoritedId)->first();
    }

    public function create(array $data): Favorite
    {
        return $this->model::create($data);
    }

    public function delete(int $id): void
    {
        $this->model::destroy($id);
    }

    public function getFavoritedYesterday(): Collection
    {
        return DB::table('favorites')->select('favorited_id', DB::raw('count(*) as total'))->whereDate('created_at', Carbon::yesterday())->groupBy('favorited_id')->get();
    }
}
