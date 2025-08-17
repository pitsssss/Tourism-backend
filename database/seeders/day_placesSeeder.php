<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\day_place;
class day_placesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            day_place::create( [
            'day_id'=> 1,
            'place_id' => 1,
            
        ] );
    }
}
