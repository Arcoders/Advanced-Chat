<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

class GroupsController extends Controller
{

    public function groups()
    {

        return response()->json(Group::myGroups(), 200);

    }

}