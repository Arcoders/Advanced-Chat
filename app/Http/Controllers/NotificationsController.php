<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{

    public function count()
    {

        return response()->json(Auth::user()->unreadnotifications->count(), 200);

    }

    public function all()
    {

        return response()->json(Auth::user()->notifications()->select('data', 'read_at', 'created_at')->get(), 200);

    }

    public function markAsRead()
    {

        $user = Auth::user();

        foreach ($user->unreadnotifications as $notification) :

            $notification->markAsRead();

        endforeach;

        if ($user->notifications->count() >= 10) $user->notifications()->delete();

        return response()->json('done', 200);

    }

}
