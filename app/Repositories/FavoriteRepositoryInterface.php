<?php

namespace App\Repositories;

use App\Models\Favorite;
use Illuminate\Support\Collection;

interface FavoriteRepositoryInterface
{
    public function getByFavoritedId(int $userId, int $favoritedId): null|Favorite;
    public function create(array $data): Favorite;
    public function delete(int $id): void;
    public function getFavoritedYesterday(): Collection;
}
