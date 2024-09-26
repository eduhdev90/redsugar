<?php

namespace App\Repositories\User;

use App\Models\UserPhoto;
use Exception;

interface UserPhotoRepositoryInterface
{
    public function getPhotoById(int $id): Exception|UserPhoto;
    public function create(array $data): UserPhoto;
    public function update(int $id, array $data): Exception|UserPhoto;
    public function countPhotos(int $userProfileId): int;
    public function delete(int $id): void;
}
