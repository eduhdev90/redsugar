<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("Usuário", "Endpoint relacionado ao usuário")]
#[Subgroup("Cadastro", "Endpoint para cadastro de usuário")]
class UniqueEmailController extends Controller
{
    /**
     * Email único
     */
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->user()->id ?? '';
        $request->validate([
            'email' => ['required', 'email:rfc,strict', 'unique:users,email,' . $id]
        ]);

        return response()->json([], 204);
    }
}
