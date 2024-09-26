<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\UserProfile;
use App\Services\User\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("Usuário", "Endpoint relacionado ao usuário")]
#[Subgroup("Perfil", "Endpoint para dados do usuário")]
class UserProfileController extends Controller
{
    public function __construct(
        private UserProfileService $userService
    ) {
    }

    public function loggedUser(Request $request): JsonResponse|UserProfileResource
    {
        try {
            return $this->userService->findLoggedUser($request);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], $th->getCode());
        }
    }

    /**
     * Atualizar perfil
     */
    #[ResponseFromApiResource(UserProfileResource::class, UserProfile::class)]
    public function update(UpdateUserProfileRequest $request)
    {
        try {
            return $this->userService->update($request->user()->id, $request);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 404);
        }
    }
}
