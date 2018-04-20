<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{

    public function chats()
    {

        $chats = [

            'groups' => $this->withLastMessage(Auth::user()->groups),

            'friends' => $this->withLastMessage(Auth::user()->chats()),

            'chatsIds' => Auth::user()->chatsIds()

        ];

        return response()->json($chats, 200);

    }

    protected function withLastMessage($chats)
    {

        $result = array();

        foreach ($chats as $chat) :

            array_push($result, collect($chat)->prepend($chat->messages->last(), 'msg'));

        endforeach;

        return $result;

    }

}
