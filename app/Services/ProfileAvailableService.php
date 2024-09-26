<?php

namespace App\Services;

use App\Filters\ProfileAvailableClosestFilter;
use App\Filters\ProfileAvailableFavoritedMeFilter;
use App\Filters\ProfileAvailableFavoritesFilter;
use App\Filters\ProfileAvailableNewsFilter;
use App\Filters\ProfileAvailableSearchFilter;
use App\Filters\ProfileAvailableVisitedMeFilter;
use App\Http\Resources\ProfileAvailableListResource;
use App\Http\Resources\ProfileAvailableResource;
use App\Models\Views\ProfileAvailableView;
use App\Repositories\ProfileViewRepositoryInterface;
use App\Repositories\User\ProfileAvailableRepositoryInterface;
use App\Services\Plan\SubscriptionService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProfileAvailableService
{

    public function __construct(
        protected ProfileAvailableRepositoryInterface $repository,
        protected ProfileViewRepositoryInterface $profileViewRepository,
        protected SubscriptionService $subscriptionService
    ) {
    }

    public function news(): ResourceCollection
    {
        return ProfileAvailableListResource::collection($this->repository->getFiltered(new ProfileAvailableNewsFilter));
    }

    public function favorites(): ResourceCollection
    {
        return ProfileAvailableListResource::collection($this->repository->favorites(new ProfileAvailableFavoritesFilter));
    }

    public function favoritedme(): ResourceCollection
    {
        return ProfileAvailableListResource::collection($this->repository->favorites(new ProfileAvailableFavoritedMeFilter));
    }

    public function closest(): ResourceCollection
    {
        return ProfileAvailableListResource::collection($this->repository->getFiltered(new ProfileAvailableClosestFilter));
    }

    public function visitedme(): ResourceCollection
    {
        return ProfileAvailableListResource::collection($this->repository->getFiltered(new ProfileAvailableVisitedMeFilter));
    }

    public function search(): ResourceCollection
    {
        return ProfileAvailableListResource::collection($this->repository->getFiltered(new ProfileAvailableSearchFilter));
    }

    public function getProfile(int $userProfileId, Request $request): ?ProfileAvailableResource
    {

        if (auth()->user()->profile_available === null) {
            throw new HttpException(400, "Perfil aguardando aprovação!");
        }

        $profileToVisit = $this->repository->getById((int) $request->route('id'));

        if (empty($profileToVisit)) {
            Log::info('Perfil não encontrado para id {id}', ['id' => $request->route('id')]);
            throw new ModelNotFoundException("Perfil não encontrado");
        }

        $visited = $this->profileViewRepository->getVisitedTodayByVisitorId($userProfileId, $profileToVisit->id);

        if (empty($visited) && $profileToVisit->id !== $userProfileId) {

            if (!$this->subscriptionService->canVisitProfile($userProfileId)) {
                throw new HttpException(422, "Você atingiu o limite de visitas para o seu plano");
            }

            Log::info('Marcando visita em perfil de id {id}', ['id' => $request->route('id')]);
            $profileToVisit->views()->create(['visitor_id' => $userProfileId]);
        }

        return new ProfileAvailableResource($profileToVisit);
    }

    public function getById(int $id): ?ProfileAvailableView
    {
        return $this->repository->getById($id);
    }

    public function getByExternalId(string $externalId): ?ProfileAvailableView
    {
        return $this->repository->getByExternalId($externalId) ?? null;
    }

    public function getVisitedYesterday(): Collection
    {
        return $this->profileViewRepository->getVisitedYesterday();
    }
}
