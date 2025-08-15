<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transportation;

class TransportationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
    {
        $transportations = [
            [
                'name' => 'باص سياحي',
                'image' => 'bus.jpg',
                'price_per_day' => 150,
                'rating' => 4,
                'capacity' => 30,
            ],
            [
                'name' => 'سيارة فان',
                'image' => 'van.jpg',
                'price_per_day' => 100,
                'rating' => 5,
                'capacity' => 12,
            ],
            [
                'name' => 'سيارة خاصة',
                'image' => 'car.jpg',
                'price_per_day' => 80,
                'rating' => 5,
                'capacity' => 4,
            ],
            [
                'name' => 'دراجة كهربائية',
                'image' => 'ebike.jpg',
                'price_per_day' => 25,
                'rating' => 3,
                'capacity' => 1,
            ],
        ];

        foreach ($transportations as $transportation) {
            Transportation::create($transportation);
        }
    }
}
