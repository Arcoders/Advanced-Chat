<?php

namespace App\Traits;

use App\Friendship;

trait Friends
{

    public function addFriend($recipientId)
    {

        $status = $this->checkFriendship($recipientId);

        if ($status == 'not_friends')
        {
            Friendship::create(['requester' => $this->id, 'requested' => $recipientId]);

            return 'waiting';
        }

        return $status;

    }

    public function checkFriendship($id)
    {

        if ($this->id == $id)  return 'same_user';

    }

}