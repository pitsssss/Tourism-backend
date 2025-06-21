<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Restaurant;


class RestaurantSeeder extends Seeder
{
    public function run()
    {
       Restaurant::insert([
            [
                'name' => 'Al Khawali Restaurant',
                'description' => 'Traditional Syrian food in the heart of Damascus.',
                'cuisine_type' => 'Syrian',
                'location' => 'Old Damascus',
                'phone_number' => '011-2233445',
                'rating' => 4.7,
                'image' => 'imgs\restaurants.img\تنزيل.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ocean Fish House',
                'description' => 'Fresh seafood dishes with a Mediterranean twist.',
                'cuisine_type' => 'Seafood',
                'location' => 'Lattakia',
                'phone_number' => '041-5566778',
                'rating' => 4.5,
                'image' => 'imgs\restaurants.img\تنزيل (1).jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    
}
}

