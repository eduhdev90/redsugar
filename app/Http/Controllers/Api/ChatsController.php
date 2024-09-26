<?php

namespace App\Http\Controllers\Api;

use App\Models\Chats;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatsController extends Controller
{
    public function create(Request $request)
    {
        return Chats::create($request->all());
    }

    public function list(Request $request){
        return Chats::where('user_profile1_id',$request->id)
            ->orWhere('user_profile2_id',$request->id)
            ->with('user1','user2','user1.user','user2.user')
            ->get();
    }

    public function update(Request $request)
    {
        $chat = Chats::find($request->id);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }

        $chat->update([
            'messages' => $request->messages
        ]);

        return $chat;
    }

    public function messages(Request $request)
    {
        $chat = Chats::find($request->id);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }

        return response()->json($chat->messages);
    }


}
