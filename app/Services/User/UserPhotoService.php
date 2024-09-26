<?php

namespace App\Services\User;

use App\Http\Resources\UserPhotoResource;
use App\Http\Resources\UserProfileResource;
use App\Repositories\User\RegisterRepositoryInterface;
use App\Repositories\User\UserPhotoRepositoryInterface;
use App\ValueObjects\ApprovalStatus;
use App\ValueObjects\PhotoVisibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserPhotoService
{
    public function __construct(
        protected RegisterRepositoryInterface $registerRepository,
        protected UserPhotoRepositoryInterface $userPhotoRepository
    ) {
    }

    public function store(Request $request): UserPhotoResource
    {
        $profile = $request->user()->profile;

        $totalPhotos = $this->userPhotoRepository->countPhotos($profile->id);
        if ($totalPhotos >= 10) {
            throw new HttpException(422, "Você já cadastrou o limite máximo de 10 fotos");
        }

        $path = $request->file('photo')->store('photos/' . $profile->id, ['disk' => 'public']);
        $dataPhoto = [
            'path' => $path,
            'visibility' => PhotoVisibility::PUBLIC->value,
            'user_profile_id' => $profile->id
        ];
        $photo = $this->userPhotoRepository->create($dataPhoto);
        return new UserPhotoResource($photo);
    }

    public function delete(int $photoId, Request $request): void
    {
        $profile = $request->user()->profile;

        $photo = $this->userPhotoRepository->getPhotoById($photoId);

        if (empty($photo)) {
            throw new HttpException(404, "Foto não encontrada");
        }

        if ($photo->user_profile_id !== $profile->id) {
            throw new HttpException(404, "Foto não encontrada");
        }

        if ($photo->id === $profile->profile_photo_id) {
            throw new HttpException(422, "Para deletar uma foto de perfil, primeiro defina outra foto como perfil");
        }

        Storage::disk('public')->delete($photo->path);

        $this->userPhotoRepository->delete($photo->id);
    }

    public function setPhotoProfile(int $photoId, Request $request): UserProfileResource
    {
        $profile = $request->user()->profile;

        $photo = $this->userPhotoRepository->getPhotoById($photoId);

        if (empty($photo)) {
            throw new HttpException(404, "Foto não encontrada");
        }

        if ($photo->user_profile_id !== $profile->id) {
            throw new HttpException(404, "Foto não encontrada");
        }

        if ($photo->status !== ApprovalStatus::APPROVED->value) {
            throw new HttpException(422, "Não é possível definir uma foto pendente ou rejeitada como foto de perfil");
        }

        if ($photo->id !== $profile->profile_photo_id) {

            $data = $request->all();

            $data['profile_photo_id'] = $photo->id;
            $data['profile_image'] = $photo->path;
            $data = $this->registerRepository->update($profile->user_id, $data);

            return new UserProfileResource($data);
        }

        return new UserProfileResource($profile);
    }

    public function setPrivatePhoto(int $photoId, Request $request): UserPhotoResource
    {
        $profile = $request->user()->profile;

        $photo = $this->userPhotoRepository->getPhotoById($photoId);

        if (empty($photo)) {
            throw new HttpException(404, "Foto não encontrada");
        }

        if ($photo->user_profile_id !== $profile->id) {
            throw new HttpException(404, "Foto não encontrada");
        }

        if ($photo->id === $profile->profile_photo_id) {
            throw new HttpException(422, "Não é possível definir uma foto de perfil como privada");
        }

        if ($photo->visibility !== PhotoVisibility::PRIVATE->value) {
            $photo = $this->userPhotoRepository->update($photoId, ['visibility' => PhotoVisibility::PRIVATE->value]);
        }

        return new UserPhotoResource($photo);
    }

    public function setPublicPhoto(int $photoId, Request $request): UserPhotoResource
    {
        $profile = $request->user()->profile;

        $photo = $this->userPhotoRepository->getPhotoById($photoId);

        if (empty($photo)) {
            throw new HttpException(404, "Foto não encontrada");
        }

        if ($photo->user_profile_id !== $profile->id) {
            throw new HttpException(404, "Foto não encontrada");
        }

        if ($photo->visibility !== PhotoVisibility::PUBLIC->value) {
            $photo = $this->userPhotoRepository->update($photoId, ['visibility' => PhotoVisibility::PUBLIC->value]);
        }

        return new UserPhotoResource($photo);
    }
}
