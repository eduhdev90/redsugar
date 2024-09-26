<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterStepFiveRequest;
use App\Http\Requests\RegisterStepFourRequest;
use App\Http\Requests\RegisterStepOneRequest;
use App\Http\Requests\RegisterStepSixRequest;
use App\Http\Requests\RegisterStepThreeRequest;
use App\Http\Requests\RegisterStepTwoRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\UserProfile;
use App\Services\User\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("Usuário", "Endpoint relacionado ao usuário")]
#[Subgroup("Cadastro", "Endpoint para cadastro de usuário")]
#[Authenticated]
class RegisterController extends Controller
{
    public function __construct(protected UserProfileService $registerService)
    {
    }

    /**
     * Etapa 1
     */
    #[ResponseFromApiResource(UserProfileResource::class, UserProfile::class)]
    public function register_step_one(RegisterStepOneRequest $request)
    {
        return $this->updateUserProfile($request);
    }

    /**
     * Etapa 2
     */
    #[ResponseFromApiResource(UserProfileResource::class, UserProfile::class)]
    public function register_step_two(RegisterStepTwoRequest $request)
    {
        return $this->updateUserProfile($request);
    }

    /**
     * Etapa 3
     */
    #[ResponseFromApiResource(UserProfileResource::class, UserProfile::class)]
    #[BodyParam('beard_size', 'integer', 'Possui / tamanho da barba (1, 2, 3, 4, 5) - Obrigatório p/ homens', required: false, example: '1')]
    #[BodyParam('beard_color', 'integer', 'Cor da barba (1, 2, 3, 4, 5, 6, 7)', required: false, example: '2')]
    public function register_step_three(RegisterStepThreeRequest $request)
    {
        return $this->updateUserProfile($request);
    }

    /**
     * Etapa 4
     */
    #[ResponseFromApiResource(UserProfileResource::class, UserProfile::class)]
    #[BodyParam('monthly_income', 'integer', 'Renda Mensal (1, 2, 3, 4, 5, 6, 7, 8) - Obrigatório para Sugar Daddy / Mommy', required: false, example: '1')]
    #[BodyParam('personal_patrimony', 'integer', 'Patrimônio (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11) - Obrigatório para Sugar Daddy / Mommy', required: false, example: '3')]
    public function register_step_four(RegisterStepFourRequest $request)
    {
        return $this->updateUserProfile($request);
    }

    /**
     * Etapa 5
     */
    #[ResponseFromApiResource(UserProfileResource::class, UserProfile::class)]
    public function register_step_five(RegisterStepFiveRequest $request)
    {
        return $this->updateUserProfile($request);
    }

    /**
     * Etapa 6
     */
    #[ResponseFromApiResource(UserProfileResource::class, UserProfile::class)]
    public function register_step_six(RegisterStepSixRequest $request)
    {
        return $this->updateUserProfile($request);
    }

    private function updateUserProfile(Request $request): UserProfileResource|JsonResponse
    {
        try {
            return $this->registerService->update($request->user()->id, $request);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 404);
        }
    }

    /**
     * Decrementar etapa do registro
     */
    #[ResponseFromApiResource(UserProfileResource::class, UserProfile::class)]
    public function decrementStep(Request $request)
    {
        try {
            $user = $request->user();
            $profile = $user->profile;

            if ($profile->current_step > 1) {
                $profile->current_step -= 1;
                $profile->save();
            }

            return new UserProfileResource($profile);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 404);
        }
    }
}
