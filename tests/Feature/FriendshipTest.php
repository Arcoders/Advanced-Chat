<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class FriendshipTest extends TestCase
{

    public function test_user_can_not_send_a_request_to_himself()
    {

        $user = factory(User::class)->create();

        $addFriend = $user->addFriend($user->id);

        $this->assertEquals('not_friends', $addFriend);

        $this->assertEquals('same_user', $user->checkFriendship($user->id));
        
    }

}
