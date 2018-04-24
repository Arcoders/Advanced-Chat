<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Online extends Model
{

    protected $fillable = ['group_chat', 'user_id', 'friend_chat'];

    protected $table = 'online';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
