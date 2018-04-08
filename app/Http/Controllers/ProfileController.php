<?php

namespace App\Http\Controllers;

use App\Traits\UploadFiles;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    use UploadFiles;

    public function users()
    {

        $excluded = Auth::user()->friends('ids');

        array_push($excluded, Auth::id());

        return response()->json(User::all()->except($excluded)->random(8), 200);

    }

    public function user(User $user)
    {

        return response()->json($user, 200);

    }

    public function edit(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'name' => 'required|min:3|max:25',
            'status' => 'required|min:5|max:70',
            'avatar' => 'image|mimes:jpeg,jpg,png,gif|max:1000',
            'cover' => 'image|mimes:jpeg,jpg,png,gif|max:1000'
        ]);

        if ($request->file('avatar'))
        {

            $this->deleteImage($user->avatar);

            $user->avatar = $this->processImage($request->file('avatar'), 'avatars');

        }

        if ($request->file('cover'))
        {

            $this->deleteImage($user->cover);

            $user->cover = $this->processImage($request->file('cover'), 'covers');

        }

        $user->name = $request['name'];
        $user->status = $request['status'];

        $user->save();

        return response()->json(['user' => $user, 'message' => 'User edited successfully'], 200);

    }

}
