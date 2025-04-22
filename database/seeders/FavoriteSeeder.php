<?php

use Illuminate\Database\Seeder;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Destination;

class FavoriteSeeder extends Seeder
{
    public function run()
    {
        Favorite::create([
            'user_id' => User::inRandomOrder()->first()->id,
            'destination_id' => Destination::inRandomOrder()->first()->id,
        ]);
    }
}

