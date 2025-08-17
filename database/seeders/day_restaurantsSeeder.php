<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\day_restaurant;
class day_restaurantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            day_restaurant::create( [
            'day_id'=> 1,
            'restaurant_id' => 1,
            
        ] );
    }
}
