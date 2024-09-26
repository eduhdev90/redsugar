<?php

namespace App\Services\User;

use App\Http\Resources\UserProfileResource;
use App\Repositories\ProfileViewRepositoryInterface;
use App\Repositories\User\RegisterRepositoryInterface;
use App\Repositories\User\UserPhotoRepositoryInterface;
use App\Repositories\User\UserProfileRepositoryInterface;
use App\ValueObjects\Gender;
use App\ValueObjects\PhotoVisibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserProfileService
{
    public function __construct(
        protected RegisterRepositoryInterface $registerRepository,
        protected UserPhotoRepositoryInterface $userPhotoRepository,
        protected UserProfileRepositoryInterface $userProfileRepositoryInterface,
        protected ProfileViewRepositoryInterface $profileViewRepository
    ) {
    }

    public function findLoggedUser(Request $request): UserProfileResource
    {
        if (empty($request->user()->profile)) {
            throw new HttpException(404, "Usuário não encontrado");
        }
        return new UserProfileResource($request->user()->profile->loadCount('views'));
    }

    public function update(int $id, Request $request): UserProfileResource
    {
        $data = $request->validated();
        $profile = $this->registerRepository->getByUserId($id);

        if (isset($data['current_step']) && (int) $data['current_step'] === $profile->current_step + 1) {
            $profile->current_step = (int) $data['current_step'];
        }

        if ($profile->gender === Gender::FEMALE->value) {
            $data = $request->except(['beard_size', 'beard_color']);
        }

        unset($data['profile']);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('photos/' . $profile->id, ['disk' => 'public']);
            $dataPhoto = [
                'path' => $path,
                'visibility' => PhotoVisibility::PUBLIC->value,
                'user_profile_id' => $profile->id
            ];

            if (!empty($profile->profile_image)) {
                Storage::disk('public')->delete($profile->profile_image);
                $photo = $this->userPhotoRepository->update($profile->profile_photo_id, $dataPhoto);
            } else {
                $photo = $this->userPhotoRepository->create($dataPhoto);
            }

            $data['profile_photo_id'] = $photo->id;
            $data['profile_image'] = $photo->path;
        } else {
            unset($data['profile_photo_id']);
            unset($data['profile_image']);
        }

        $updatedProfile = $this->registerRepository->update($id, $data);

        return new UserProfileResource($updatedProfile);
    }
}
