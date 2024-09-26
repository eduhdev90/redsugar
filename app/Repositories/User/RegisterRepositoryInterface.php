<?php

namespace App\Repositories\User;

use App\Models\UserProfile;

interface RegisterRepositoryInterface
{
    public function update(int $id, array $data): UserProfile;
    public function getByUserId(int $id): UserProfile;
}
