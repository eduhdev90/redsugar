<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("Usuário", "Endpoint relacionado ao usuário")]
#[Subgroup("Cadastro", "Endpoint para cadastro de usuário")]
class UniqueUsernameController extends Controller
{
    /**
     * Username único
     */
    public function __invoke(Request $request)
    {
        $id = $request->user()->id ?? '';
        $request->validate([
            'username' => ['required', 'unique:users,name,' . $id]
        ]);

        return response()->json([], 204);
    }
}
