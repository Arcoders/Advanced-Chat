<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{

    use SoftDeletes;

    protected $fillable = ['name', 'user_id', 'avatar'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('group_id', 'user_id');
    }

    public function scopeAllGroups($query)
    {
        return $query->where('user_id', Auth::id())->withTrashed()->paginate(4);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'group_chat');
    }

}
