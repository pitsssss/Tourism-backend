<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PlaceImage;
class place_imagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlaceImage::insert([
            [
                'place_id' => 1,
                'image_path' => 'imgs\place.img\تنزيل (2).jpg',
                
            ],]);
    }
}
