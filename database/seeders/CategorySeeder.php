<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
   {
    $categories = [
        ['name' => 'Sea', 'photo' => 'imgs\categories.img\تنزيل.jpg'],
        ['name' => 'Beach', 'photo' => 'imgs\categories.img\تنزيل.jpg.jpg'],
        ['name' => 'Mountain', 'photo' => 'imgs\categories.img\تنزيل.jpg.jpg'],
        ['name' => 'Historical', 'photo' => 'imgs\categories.img\تنزيل.jpg.jpg'],
        ['name' => 'Desert', 'photo' => 'imgs\categories.img\تنزيل.jpg.jpg'],
        ['name' => 'City', 'photo' => 'imgs\categories.img\تنزيل.jpg'],
    ];

    foreach ($categories as $category) {
        Category::create([
            'name' => $category['name'],
            'categorie_photo_path' => $category['photo']
        ]);
    }
}
}

