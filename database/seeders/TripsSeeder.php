<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\Category;
use App\Models\Hotel;
use App\Models\Activity;
use App\Models\Day;
use App\Models\TourGuide;


class TripsSeeder extends Seeder
{
    public function run()
    {
        Trip::create([
            'name' => ' To Palmyra',
            'description' => 'Ancient city in the desert',
            'image' => 'imgs\categories.img\تنزيل.jpg',
            'category_id' => 1,
            //'transport' => 'Bus',
            'price' => 500,
            'hotel_id' => 1,
            'count_days' => 4,
             'governorate_id' => 1,
            'guide_id'=> 1
        ]);


        Trip::create([
            'name' => ' To Lattakia',
            'description' => 'best beach in the country',
            'category_id' => 2,
           // 'transport' => 'Bus',
            'price' => 300,
            'hotel_id' => 2,
            'count_days' => 4,
             'governorate_id' => 1,
              'guide_id'=> 1
        ]);
    

Trip::create(
    [
    'name' => 'Trip to Palmyra',
   // 'transport' => 'Bus',
    'description' => 'Explore the ancient ruins of Palmyra.',
    'start_date' => ('5/5/2024'),
    'image' => 'imgs\trips.img\تنزيل.jpg',
    'hotel_id' => 1,
    'category_id' => 1, 
    'price' => 150000,
    'count_days' => 3,
    'governorate_id' => 1,
      'guide_id'=> 1,
    'created_at' => now(),
    'updated_at' => now(),
    ]);
    }
}
