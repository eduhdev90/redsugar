<?php

namespace App\Services;

use App\Http\Resources\FavoriteResource;
use App\Repositories\FavoriteRepositoryInterface;
use App\Repositories\User\UserProfileRepositoryInterface;
use App\Services\Plan\SubscriptionService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FavoriteService
{
    public function __construct(
        private FavoriteRepositoryInterface $repository,
        private UserProfileRepositoryInterface $userProfileRepository,
        private SubscriptionService $subscriptionService
    ) {
    }

    public function store(int $userProfileId, Request $request): Exception|FavoriteResource
    {
        if (!$this->subscriptionService->canAddFavorite($userProfileId)) {
            throw new HttpException(422, "Você atingiu o limite de favorites para o seu plano");
        }

        if ($userProfileId === (int) $request->id) {
            throw new HttpException(422, "Não é permitido se favoritar");
        }

        $favorite = $this->repository->getByFavoritedId($userProfileId, $request->id);

        if ($favorite) {
            throw new HttpException(422, "Usuário já favoritado");
        }

        $profile = $this->userProfileRepository->getById($request->id);
        if (empty($profile)) {
            throw new ModelNotFoundException("Usuário não encontrado", 404);
        }

        $favorite = $this->repository->create([
            'user_profile_id' => $userProfileId,
            'favorited_id' => $request->id
        ]);

        return new FavoriteResource($favorite);
    }

    public function delete(int $userProfileId, Request $request): void
    {
        $favorite = $this->repository->getByFavoritedId($userProfileId, $request->route('id'));

        if (empty($favorite)) {
            throw new ModelNotFoundException("Favorito não encontrado", 404);
        }

        $this->repository->delete($favorite->id);
    }

    public function getFavoritedYesterday(): Collection
    {
        return $this->repository->getFavoritedYesterday();
    }
}
