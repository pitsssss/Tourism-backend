<?php

use Illuminate\Database\Seeder;
use App\Models\Destination;
use App\Models\Category;

class DestinationSeeder extends Seeder
{
    public function run()
    {
        Destination::create([
            'name' => 'Palmyra',
            'description' => 'Ancient city in the desert',
            'category_id' => Category::inRandomOrder()->first()->id
        ]);
        // Add more destinations
    }
}

