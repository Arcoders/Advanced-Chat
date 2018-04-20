<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{

    public function latest(Request $r)
    {

        return response()->json(Message::latestMessages($r->room_name, $r->chat_id, 10), 200);

    }

}
