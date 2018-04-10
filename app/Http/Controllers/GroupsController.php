<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupsController extends Controller
{

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

}
