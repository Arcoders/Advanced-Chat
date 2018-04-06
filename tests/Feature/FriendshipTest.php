<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class FriendshipTest extends TestCase
{

    public function test_user_can_not_send_a_request_to_himself()
    {

        $user = factory(User::class)->create();

        $user->addFriend($user->id);

        $this->assertCount(0, $user->requestsReceived());
        $this->assertCount(0, $user->requestsSent());

        $this->assertEquals('same_user', $user->checkFriendship($user->id));

    }

    public function test_user_can_add_a_friend()
    {

        $sender = factory(User::class)->create();
        $recipientOne = factory(User::class)->create();
        $recipientTwo = factory(User::class)->create();

        $sender->addFriend($recipientOne->id);
        $sender->addFriend($recipientTwo->id);

        $this->assertCount(2, $sender->requestsSent());
        $this->assertCount(1, $recipientOne->requestsReceived());
        $this->assertCount(1, $recipientTwo->requestsReceived());

        $this->assertNotTrue($sender->isFriendWith($recipientOne->id));
        $this->assertNotTrue($recipientTwo->isFriendWith($sender->id));

    }

    public function test_change_status_to_pending_and_waiting()
    {

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        $sender->addFriend($recipient->id);

        $this->assertEquals('pending', $recipient->checkFriendship($sender->id));
        $this->assertEquals('waiting', $sender->checkFriendship($recipient->id));

    }

    public function test_it_returns_accepted_friendship()
    {

        $sender = factory(User::class)->create();
        $recipients = factory(User::class, 5)->create();

        foreach ($recipients as $recipient) {

            $sender->addFriend($recipient->id);

            $recipient->acceptFriend($sender->id);

            $this->assertEquals('friends', $sender->checkFriendship($recipient->id));

        }

        $this->assertCount(5, $sender->friends());

    }

    public function test_user_can_reject_the_request()
    {

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        $sender->addFriend($recipient->id);

        $this->assertEquals('pending', $recipient->checkFriendship($sender->id));

        $this->assertEquals('waiting', $sender->checkFriendship($recipient->id));

        $recipient->rejectFriendship($sender->id);

        $this->assertEquals('not_friends', $recipient->checkFriendship($sender->id));

    }

    public function test_user_can_delete_a_friend()
    {

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        $sender->addFriend($recipient->id);

        $recipient->acceptFriend($sender->id);

        $this->assertEquals('friends', $sender->checkFriendship($recipient->id));

        $this->assertCount(1, $sender->friends());
        $this->assertCount(1, $recipient->friends());

        $recipient->rejectFriendship($sender->id);

        $this->assertEquals('not_friends', $sender->checkFriendship($recipient->id));

        $this->assertCount(0, $sender->friends());
        $this->assertCount(0, $recipient->friends());
    }

}
