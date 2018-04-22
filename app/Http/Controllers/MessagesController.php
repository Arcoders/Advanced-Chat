<?php

namespace App\Http\Controllers;

use App\Message;
use App\Traits\TriggerPusher;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{

    use UploadFiles;
    use TriggerPusher;

    public function latest(Request $r)
    {

        return response()->json(Message::latestMessages($r->room_name, $r->chat_id, 5), 200);

    }

    public function send(Request $r)
    {

        $photo = null;

        if ($r->file('photo')) {

            $r->validate(['photo' => 'image|mimes:jpeg,jpg,png,gif|max:1500']);

            $photo =  $this->processImage($r->file('photo'), 'messages');

        } else {

            $r->validate(['messageText' => 'required|min:2']);

        }

        $message = Message::create([

            'body' => $r->messageText,

            'user_id' => Auth::id(),

            $r->roomName => $r->chatId,

            'photo' => $photo
        ]);

        $this->pushMessage([

            'msg' => $message,

            'room' => "$r->roomName-$r->chatId"

        ]);

    }

    protected function pushMessage(array $data)
    {

        $this->trigger($data['room'], 'newMessage', ['message' => $data['msg'], 'user' => Auth::user()]);

    }

}
