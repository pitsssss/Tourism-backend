<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TourGuide;

class GuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TourGuide::insert([
            [
                'name' => 'خالد المصري',
                'phone' => '0933445566',
                'rating' => 4.5,
                'image' => 'guides/khaled.jpg',
            ],
            [
                'name' => 'ليلى العلي',
                'phone' => '0988776655',
                'rating' => 4.8,
                'image' => 'guides/layla.jpg',
            ],
            [
                'name' => 'مازن توفيق',
                'phone' => '0999112233',
                'rating' => 4.2,
                'image' => 'guides/mazen.jpg',
            ],
        ]);
    }

}
