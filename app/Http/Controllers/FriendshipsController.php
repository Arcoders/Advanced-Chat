<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipsController extends Controller
{

    public function chats()
    {
        return Auth::user()->chats();
    }

}
