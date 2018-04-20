<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker)
{

    return [

        'name' => $faker->name,

        'email' => $faker->unique()->safeEmail,

        'status' => $faker->text(70),

        'avatar' => (rand(1, 2) === 1) ? "https://api.adorable.io/avatars/285/$faker->firstName.png" : NULL,

        'cover' => $faker->imageUrl(800, 400),

        'password' => bcrypt('secret'),

        'remember_token' => str_random(10),

    ];

});

$factory->define(\App\Friendship::class, function()
{

    return [

        'requester' => function ()
        {
            return factory(\App\User::class)->create()->id;
        },

        'requested' => function ()
        {
            return factory(\App\User::class)->create()->id;
        },

        'status' => 0

    ];

});

$factory->define(\App\Group::class,  function (Faker $faker) {

    return [

        'name' => $faker->name,

        'avatar' => "https://api.adorable.io/avatars/285/$faker->firstName.png",

        'user_id' => function ()
        {
            return factory(\App\User::class)->create()->id;
        },

        'deleted_at' => (rand(1, 3) === 1) ? $faker->dateTimeThisMonth()->format('Y-m-d H:i:s') : NULL

    ];

});

$factory->define(\App\Message::class, function (Faker $faker) {

    $photo = (rand(1, 4) === 1) ? $faker->imageUrl(800, 400) : NULL;

    return [

        'user_id' => function ()
        {
            return factory(\App\User::class)->create()->id;
        },

        'friend_chat' => function ()
        {
            return factory(\App\Friendship::class)->create()->id;
        },

        'body' => $faker->text(70),

        'photo' => $photo

    ];

});