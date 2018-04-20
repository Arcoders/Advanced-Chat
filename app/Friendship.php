<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friendship extends Model
{

    use SoftDeletes;

    protected $fillable = ['requested', 'requester', 'status'];

    public function scopeAccepted($query)
    {
        return $query->where('status', 1);
    }

    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    public function scopeWhereSender($query, $id)
    {
        return $query->where('requester', $id);
    }

    public function scopeWhereRecipient($query, $id)
    {
        return $query->where('requested', $id);
    }

    public function scopeBetweenUsers($query, $sender, $recipient)
    {

        $query->where(function ($q) use ($sender, $recipient) {

                $q->whereSender($sender)->whereRecipient($recipient);

            })->orWhere(function ($q) use ($sender, $recipient) {

                $q->whereSender($recipient)->whereRecipient($sender);

            });

    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'requester');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'requested');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'friend_chat');
    }

}
