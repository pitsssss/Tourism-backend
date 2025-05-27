<?php

use App\Models\Trips;
use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\Destination;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        Restaurant::create([
            'name' => 'Palmyra Grill',
            'description' => 'Local Syrian cuisine near ruins',
            'destination_id' => Trips::inRandomOrder()->first()->id
        ]);
    }
}

