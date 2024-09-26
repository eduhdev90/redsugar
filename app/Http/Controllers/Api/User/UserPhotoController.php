<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserPhotoRequest;
use App\Http\Resources\UserPhotoResource;
use App\Http\Resources\UserProfileResource;
use App\Models\UserPhoto;
use App\Models\UserProfile;
use App\Services\User\UserPhotoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("Usuário", "Endpoint relacionado ao usuário")]
#[Subgroup("Fotos", "Endpoint para edição de fotos")]
class UserPhotoController extends Controller
{
    public function __construct(
        private UserPhotoService $userPhotoService
    ) {
    }

    /**
     * Adicionar foto
     */
    #[ResponseFromApiResource(UserPhotoResource::class, UserPhoto::class, 201)]
    public function store(CreateUserPhotoRequest $request): JsonResponse|UserPhotoResource
    {
        try {
            return $this->userPhotoService->store($request);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], $th->getCode() ?? 404);
        }
    }

    /**
     * Deletar foto
     */
    #[Response(null, 204)]
    public function destroy(Request $request): JsonResponse
    {
        try {
            $this->userPhotoService->delete($request->route('id'), $request);
            return response()->json(null, HttpResponse::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            $code = $th->getCode() === 0 ? 404 : $th->getCode();
            return response()->json(['message' => $th->getMessage()], $code);
        }
    }

    /**
     * Setar foto de perfil
     */
    #[ResponseFromApiResource(UserProfileResource::class, UserProfile::class)]
    public function setPhotoProfile(Request $request): JsonResponse|UserProfileResource
    {
        try {
            return $this->userPhotoService->setPhotoProfile($request->route('id'), $request);
        } catch (\Throwable $th) {
            $code = $th->getCode() === 0 ? 404 : $th->getCode();
            return response()->json(['message' => $th->getMessage()], $code);
        }
    }

    /**
     * Setar foto privada
     */
    #[ResponseFromApiResource(UserPhotoResource::class, UserPhoto::class)]
    public function setPrivatePhoto(Request $request): JsonResponse|UserPhotoResource
    {
        try {
            return $this->userPhotoService->setPrivatePhoto($request->route('id'), $request);
        } catch (\Throwable $th) {
            $code = $th->getCode() === 0 ? 404 : $th->getCode();
            return response()->json(['message' => $th->getMessage()], $code);
        }
    }


    /**
     * Setar foto pública
     */
    #[ResponseFromApiResource(UserPhotoResource::class, UserPhoto::class)]
    public function setPublicPhoto(Request $request): JsonResponse|UserPhotoResource
    {
        try {
            return $this->userPhotoService->setPublicPhoto($request->route('id'), $request);
        } catch (\Throwable $th) {
            $code = $th->getCode() === 0 ? 404 : $th->getCode();
            return response()->json(['message' => $th->getMessage()], $code);
        }
    }
}
