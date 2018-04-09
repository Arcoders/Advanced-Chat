<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (range(1, 10) as $i) :

            factory(\App\Group::class)->create(['user_id' => 1])->users()->sync([1, $i]);

        endforeach;

        factory(\App\Group::class)->create([
            'name' => 'Arcoders',
            'avatar' => NULL,
            'user_id' => 1,
            'deleted_at' => NULL,
        ])->users()->sync([1, 2]);


        factory(\App\Group::class)->create([
            'name' => 'Fustal Girona',
            'avatar' => NULL,
            'user_id' => 1,
            'deleted_at' => NULL,
        ])->users()->sync([1, 2, 3]);


        factory(\App\Group::class)->create([
            'name' => 'Javascript',
            'avatar' => NULL,
            'user_id' => 1,
            'deleted_at' => NULL,
        ])->users()->sync([1, 2, 3]);


        factory(\App\Group::class)->create([
            'name' => 'Tecnología',
            'avatar' => NULL,
            'user_id' => 2,
            'deleted_at' => NULL,
        ])->users()->sync([2, 1]);

    }
}
