<?php

namespace App\Repositories\User;

use App\Models\Views\ProfileAvailableView;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProfileAvailableRepositoryInterface
{
    public function getById(int $id): ?ProfileAvailableView;
    public function getByExternalId(string $externalId): ?ProfileAvailableView;
    public function getFiltered($filters): LengthAwarePaginator;
    public function favorites($filters): LengthAwarePaginator;
}
