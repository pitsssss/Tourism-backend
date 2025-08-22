<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\Category;
use App\Models\Hotel;
use App\Models\Activity;
use App\Models\Day;
use App\Models\TourGuide;
use App\Models\TripImage;

class TripsSeeder extends Seeder
{
    public function run()
    {

 $trip =Trip::create(
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
      'transportation_id'=> 1,
    'created_at' => now(),
    'updated_at' => now(),
    ]);
    TripImage::create([
    'trip_id' => $trip->id,
    'image' => 'imgs/trips.img/palmyra_1.jpg',
]);

TripImage::create([
    'trip_id' => $trip->id,
    'image' => 'imgs/trips.img/palmyra_2.jpg',
]);

TripImage::create([
    'trip_id' => $trip->id,
    'image' => 'imgs/trips.img/palmyra_3.jpg',
]);



     $trip =Trip::create(
    [
    'name' => 'Trip to Palmyra',
   // 'transport' => 'Bus',
    'description' => 'Explore the ancient ruins of Palmyra.',
    'start_date' => ('5/5/2024'),
    'image' => 'imgs\trips.img\تنزيل.jpg',
    'hotel_id' => 1,
    'category_id' => 1,
    'price' => 1500,
    'count_days' => 4,
    'governorate_id' => 1,
      'guide_id'=> 1,
      'transportation_id'=> 1,
    'created_at' => now(),
    'updated_at' => now(),
    ]);
    TripImage::create([
    'trip_id' => $trip->id,
    'image' => 'imgs/trips.img/palmyra_1.jpg',
]);

TripImage::create([
    'trip_id' => $trip->id,
    'image' => 'imgs/trips.img/palmyra_2.jpg',
]);

TripImage::create([
    'trip_id' => $trip->id,
    'image' => 'imgs/trips.img/palmyra_3.jpg',
]);
 }
}
