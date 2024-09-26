<?php

namespace App\Repositories\User;

use App\Models\UserProfile;

class RegisterRepository implements RegisterRepositoryInterface
{
    public function __construct(protected UserProfile $model)
    {
    }

    public function update(int $id, array $data): UserProfile
    {
        $profile = $this->model::where('user_id', '=', $id)->firstOrFail();
        $profile->update($data);

        return $profile;
    }

    public function getByUserId(int $id): UserProfile
    {
        return $this->model::where('user_id', '=', $id)->firstOrFail();
    }
}
