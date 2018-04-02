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

$factory->define(App\User::class, function (Faker $faker) {

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,

        'status' => $faker->text(70),
        'avatar' => (rand(1, 2) === 1) ? "https://api.adorable.io/avatars/285/$faker->firstName.png" : NULL,
        'cover' => $faker->imageUrl(800, 400),

        'password' => encrypt('secret'),
        'remember_token' => str_random(10),
    ];

});
