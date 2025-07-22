<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Place;
class PlacesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Place::insert([
            [
                'name' => 'Old Damascus',
                'location' => 'Damascus, Syria',
                'description' => 'The historic old city of Damascus, full of ancient architecture and culture.',
                'image' => 'imgs\place.img\تنزيل (2).jpg',
                'governorate_id'=>1,
                'created_at' => now(),
                'updated_at' => now(),
            ],]);
    }
}
