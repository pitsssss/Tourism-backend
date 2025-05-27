<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        Hotel::create([
            'name' => 'Al Shaam Hotel',
            'description' => 'A luxury hotel in Damascus.',
            'address' => 'Damascus, Syria',
            'rating' => 4.5,
            'image' => 'shaam.jpg',
            'location' => 'Damascus',
            'phone_number' => '+96311222333',
            'category_id'=>3

        ]);

        Hotel::create([
            'name' => 'Aleppo Star',
            'description' => 'Modern hotel in Aleppo.',
            'address' => 'Aleppo, Syria',
            'rating' => 4.2,
            'image' => 'aleppo_star.jpg',
            'location' => 'Aleppo',
            'phone_number' => '+96311444555',
            'category_id'=>1
        ]);
    }
}
