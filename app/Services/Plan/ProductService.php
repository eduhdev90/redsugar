<?php

namespace App\Services\Plan;

use App\Http\Resources\ProductResource;
use App\Repositories\Plan\ProductRepositoryInterface;
use App\ValueObjects\Profile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {
    }

    public function getActiveAll(): ResourceCollection
    {
        return ProductResource::collection($this->repository->getActiveAll());
    }

    public function getFreeProductByProfile(int|string $profileId): ProductResource
    {
        $profile = Profile::tryFrom($profileId);
        if (empty($profile)) {
            throw new HttpException(422, "Perfil informado é inválido: $profileId");
        }

        $product = $this->repository->getFreeProductByProfile($profile->value);
        if (empty($product)) {
            throw new ModelNotFoundException("Não foi encontrado nenhum plano gratuito para o perfil de {$profile?->label()}", 404);
        }
        return new ProductResource($product);
    }
}
