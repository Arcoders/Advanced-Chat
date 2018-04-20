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

    public function scopeTotalMessages($query, $room, $chat)
    {

        return $query->where($room, $chat)->count();

    }

    public function scopeLatestMessages($query, $room, $chat, $num)
    {

        return $query->where($room, $chat)->with('user')->skip($this->totalMessages($room, $chat) - $num)->take($num)->get();

    }

}
