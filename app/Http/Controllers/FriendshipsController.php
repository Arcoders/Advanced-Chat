<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class FriendshipsController extends Controller
{

    public function check($user_id)
    {

        return response()->json(Auth::user()->checkFriendship($user_id), 200);

    }

    public function add($user_id)
    {

        return response()->json(Auth::user()->addFriend($user_id), 200);

    }

    public function accept($user_id)
    {

        return response()->json(Auth::user()->acceptFriend($user_id), 200);

    }

    public function reject($user_id)
    {

        return response()->json(Auth::user()->rejectFriendship($user_id), 200);

    }

    public function userForChat(User $user)
    {

        return response()->json($user, 200);

    }

}
