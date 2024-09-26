<?php

namespace App\Repositories\User;

use App\Models\UserPhoto;
use App\Models\UserProfile;
use Exception;

class UserPhotoRepository implements UserPhotoRepositoryInterface
{
    public function __construct(protected UserPhoto $model)
    {
    }

    public function getPhotoById(int $id): Exception|UserPhoto
    {
        return $this->model::where('id', $id)->firstOrFail();
    }

    public function create(array $data): UserPhoto
    {
        return $this->model::create($data);
    }

    public function update(int $id, array $data): Exception|UserPhoto
    {
        $photo = $this->model::where('id', $id)->firstOrFail();
        $photo->update($data);

        return $photo;
    }

    public function countPhotos(int $userProfileId): int
    {
        return $this->model::where('user_profile_id', $userProfileId)->count();
    }

    public function delete(int $id): void
    {
        $this->model::destroy($id);
    }
}
