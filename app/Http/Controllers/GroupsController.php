<?php

namespace App\Http\Controllers;

use App\Group;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupsController extends Controller
{

    use UploadFiles;

    public function groups()
    {

        return response()->json(Group::allGroups(), 200);

    }

    public function delete(Group $group)
    {

        $group->delete();

        return response()->json('Group was archived', 200);

    }

    public function restore(Group $group)
    {

        $group->restore();

        return response()->json('Group was restored', 200);

    }

    public function group(Group $group)
    {

        return response()->json(['group' => $group, 'friends' => Auth::user()->friends()], 200);

    }

    public function edit(Group $group, Request $request)
    {

        if ($request['deleteImage'])
        {

            $this->deleteImage($group->avatar);

            $group->avatar = null;

            $group->save();

            return response()->json('Avatar deleted successfully', 200);

        }

        $request['ids'] = $this->idsToArray($request['ids']);

        $request->validate([

            'name' => 'required|min:4|max:15|unique:groups,name,'.$group->id,

            'avatar' => 'image|mimes:jpeg,jpg,png|max:800',

            'ids' => 'required|array|min:1'

        ], [
            'ids.required' => 'You must select at least one user'
        ]);

        if ($request->file('avatar'))
        {

            $group->avatar = $this->processImage($request->file('avatar'), 'avatars');

        }

        $group->name = $request['name'];

        $group->save();

        $group->users()->sync($request['ids']);

        return response()->json('Group edited successfully', 200);

    }

    public function create(Request $request)
    {

        $request['ids'] = $this->idsToArray($request['ids']);

        $request->validate([

            'name' => 'required|min:4|max:15|unique:groups',

            'avatar' => 'image|mimes:jpeg,jpg,png|max:800',

            'ids' => 'required|array|min:1'

        ], [
            'ids.required' => 'You must select at least one user'
        ]);

        $avatar = null;

        if ($request->file('avatar'))
        {

            $avatar = $this->processImage($request->file('avatar'), 'avatars');

        }

        Group::create([

            'name' => $request['name'],

            'user_id' => Auth::id(),

            'avatar' => $avatar

        ])->users()->sync($request['ids']);

        return response()->json("$request->name created successfully", 200);

    }

    public function friends()
    {

        return response(Auth::user()->friends(), 200);

    }

    protected function idsToArray($request)
    {

        $ids = $request ? explode(',', $request) : [];

        array_push($ids, Auth::id());

        return $ids;

    }


}
