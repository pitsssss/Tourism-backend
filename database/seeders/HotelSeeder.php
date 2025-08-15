<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;

class HotelSeeder extends Seeder
{
    public function run(): void
     {
    //     Hotel::create([
    //         'name' => 'Al Shaam Hotel',
    //         'description' => 'A luxury hotel in Damascus.',
    //         'address' => 'Damascus, Syria',
    //         'rating' => 4.5,
    //         'image' => 'shaam.jpg',
    //         'location' => 'Damascus',
    //         'governorate_id' => 1,
    //         'phone_number' => '+96311222333',
            

    //     ]);

    //     Hotel::create([
    //         'name' => 'Aleppo Star',
    //         'description' => 'Modern hotel in Aleppo.',
    //         'address' => 'Aleppo, Syria',
    //         'rating' => 4.2,
    //         'image' => 'aleppo_star.jpg',
    //         'location' => 'Aleppo',
    //         'governorate_id' => 1,
    //         'phone_number' => '+96311444555',
            
    //     ]);

         Hotel::create([
            'name' => 'فندق الشاطئ',
            'description' => 'فندق رائع على البحر مع إطلالة مميزة وخدمات ممتازة.',
            'rating' => 4.5,
            'image' => 'main.jpg', // صورة رئيسية
            'location' => '35.5, 35.8',
            'phone_number' => '099999999',
            'governorate_id' => 1,
            'extra_images' => ['img1.jpg', 'img2.jpg', 'img3.jpg'],
            'facilities' => ['Wi-Fi', 'Dinner', 'Pool'],
        ]);
    }
}
