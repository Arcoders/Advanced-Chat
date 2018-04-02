<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(User::class)->create([
            'name' => 'Ismael Haytam',
            'status' => 'Full Stack developer todo terreno',
            'email' => 'arcoders@gmail.com',
            'avatar' => null,
            'cover' => null,
        ]);

        factory(User::class)->create([
            'name' => 'Victor crack',
            'email' => 'victor@gmail.com',
            'avatar' => null,
        ]);

        factory(User::class)->create([
            'name' => 'Marta Lopez',
            'email' => 'marta@gmail.com',
            'avatar' => null,
        ]);

        factory(User::class, 30)->create();

    }
}
