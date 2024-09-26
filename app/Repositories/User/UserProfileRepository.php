<?php

namespace App\Repositories\User;

use App\Models\UserProfile;

class UserProfileRepository implements UserProfileRepositoryInterface
{
    public function __construct(protected UserProfile $model)
    {
    }

    public function getById(int $id): null|UserProfile
    {
        return $this->model::find($id);
    }
}
