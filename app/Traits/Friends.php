<?php

namespace App\Traits;

use App\Friendship;
use App\Notifications\AcceptFriendRequest;
use App\Notifications\NewFriendRequest;
use App\User;

trait Friends
{

    use TriggerPusher;

    public function addFriend($recipientId)
    {

        $status = $this->checkFriendship($recipientId);

        if ($status == 'not_friends')
        {
            if ($this->restoreFriendship($recipientId) == 'create')
            {
                Friendship::create(['requester' => $this->id, 'requested' => $recipientId]);
            }

            $this->notifySentRequests($recipientId);

            return 'waiting';
        }

        return $status;

    }

    public function acceptFriend($senderId)
    {

        $status = $this->checkFriendship($senderId);

        if ($status == 'pending')
        {
            Friendship::betweenUsers($this->id, $senderId)->update(['status' => 1]);

            $this->notifyAcceptedRequests($senderId);

            return 'friends';
        }

        return $status;

    }

    public function rejectFriendship($userId)
    {

        $status = $this->checkFriendship($userId);

        if ($status != 'not_friends')
        {
            Friendship::betweenUsers($this->id, $userId)->delete();

            $this->removeChat($userId);

            return 'not_friends';
        }

        return $status;

    }

    public function requestsReceived()
    {
        return static::whereIn(
            'id' , Friendship::whereRecipient($this->id)->pending()->pluck('requester')->toArray()
        )->get();
    }

    public function requestsSent()
    {
        return static::whereIn(
            'id' , Friendship::whereSender($this->id)->pending()->pluck('requested')->toArray()
        )->get();
    }

    public function friends($justIds = null)
    {

        $recipients = Friendship::whereSender($this->id)->accepted()->pluck('requested')->toArray();

        $senders = Friendship::whereRecipient($this->id)->accepted()->pluck('requester')->toArray();

        $friendsIds = array_merge($recipients, $senders);

        return  ($justIds) ? $friendsIds : static::find($friendsIds);

    }

    public function isFriendWith($userId)
    {
        return $this->checkFriendship($userId) === 'friends';
    }

    public function checkFriendship($userId)
    {

        if ($this->id == $userId)  return 'same_user';

        $friendship = Friendship::betweenUsers($this->id, $userId)->first();

        if (!$friendship) return 'not_friends';

        if ($friendship->status == 1) return 'friends';

        if ($friendship->requester == $this->id) return 'waiting';

        if ($friendship->requested == $this->id) return 'pending';

    }

    public function restoreFriendship($userId)
    {

        if ($friendship = Friendship::withTrashed()->betweenUsers($this->id, $userId)->first())
        {

            $friendship->update(['requester' => $this->id, 'requested' => $userId, 'status' => 0]);

            $friendship->restore();

            return 'restored';

        }

        return 'create';

    }

    public function chats() {

        return Friendship::whereSender($this->id)->accepted()->select('id', 'requested')->with('recipient')->get()
            ->
            merge(

                Friendship::whereRecipient($this->id)->accepted()->select('id', 'requester')->with('sender')->get()

            );

    }

    public function chatsIds() {

        return array_merge(

            Friendship::whereSender($this->id)->accepted()->pluck('id')->toArray(),

            Friendship::whereRecipient($this->id)->accepted()->pluck('id')->toArray()

        );

    }

    protected function notifySentRequests($recipientId)
    {

        User::find($recipientId)->notify(new NewFriendRequest());

        $this->trigger(["user_$recipientId", "user_$this->id"], 'refreshList', []);

        $this->trigger("notification_$recipientId", 'updateCount', []);

    }

    protected function notifyAcceptedRequests($senderId)
    {
        User::find($senderId)->notify(new AcceptFriendRequest());

        $this->trigger("user_$senderId", 'refreshList', ['type' => 'chat', 'id' => $this->id]);

        $this->trigger("user_$this->id", 'refreshList', ['type' => 'chat', 'id' => $senderId]);

        $this->trigger("notification_$senderId", 'updateCount', []);
    }

    protected function removeChat($userId)
    {

        $this->trigger(["user_$userId", "user_$this->id"], 'refreshList', ['type' => 'chat']);

    }

}