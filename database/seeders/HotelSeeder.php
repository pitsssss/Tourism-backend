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
            'governorate_id' => 1,
            'phone_number' => '+96311222333',
            

        ]);

        Hotel::create([
            'name' => 'Aleppo Star',
            'description' => 'Modern hotel in Aleppo.',
            'address' => 'Aleppo, Syria',
            'rating' => 4.2,
            'image' => 'aleppo_star.jpg',
            'location' => 'Aleppo',
            'governorate_id' => 1,
            'phone_number' => '+96311444555',
            
        ]);
    }
}
