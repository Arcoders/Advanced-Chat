<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = ['group_chat', 'user_id', 'friend_chat', 'body', 'photo'];

    public function user()
    {

        return $this->belongsTo(User::class);

    }


    public function scopeLatestMessages($query, $room, $chat, $num)
    {

        return $query->where($room, $chat)->with('user')->orderBy('id', 'desc')->take($num)->get();


    }

}
