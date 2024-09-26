<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use App\Services\FavoriteService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("Usuário", "Endpoint relacionado ao usuário")]
#[Subgroup("Favoritos", "Endpoint adição e remoção de favoritos")]
class FavoriteController extends Controller
{
    public function __construct(private FavoriteService $service)
    {
    }

    /**
     * Adicionar favorito
     */
    #[ResponseFromApiResource(FavoriteResource::class, Favorite::class, 201)]
    public function store(FavoriteRequest $request): FavoriteResource
    {
        return $this->service->store(auth()->user()->profile->id, $request);
    }

    /**
     * Remover favorito
     */
    #[Response(null, 204)]
    public function destroy(Request $request): JsonResponse
    {
        $this->service->delete(auth()->user()->profile->id, $request);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
