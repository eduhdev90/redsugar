<?php

namespace App\Repositories\User;

use App\Models\UserProfile;
use Exception;

interface UserProfileRepositoryInterface
{
    public function getById(int $id): null|UserProfile;
}
