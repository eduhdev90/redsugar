<?php

namespace App\Http\Controllers\Api;

use App\Models\UsersBlock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UsersBlockController extends Controller
{
    public function list()
    {
        return UsersBlock::where('user_profile_id', auth()->user()->profile->id)

            ->with('user', 'blocked', 'user.user', 'blocked.user')
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function create(Request $request)
    {
        return UsersBlock::create([
            'user_profile_id' => auth()->user()->profile->id,
            'user_profile_id_blocked' => $request->id,
        ]);
    }

    public function isBlocked(Request $request, $id)
    {
        $userId = auth()->user()->profile->id;
        $isBlocked = UsersBlock::where('user_profile_id', $userId)
            ->where('user_profile_id_blocked', $id)
            ->exists();

        return ['isBlocked' => $isBlocked];
    }

    public function destroy($id)
    {
        $userBlock = UsersBlock::where('user_profile_id', auth()->user()->profile->id)
            ->where('user_profile_id_blocked', $id)
            ->first();

        if ($userBlock) {
            $userBlock->delete();
            return null;
        }
        return null;
    }
}
