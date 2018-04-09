<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{

    use SoftDeletes;

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('group_id', 'user_id');
    }

    public function scopeMyGroups($query)
    {

        return $query->where('user_id', Auth::id())->withTrashed()->paginate(4);

    }

}
