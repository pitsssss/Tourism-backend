<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['Sea','Beach', 'Mountain', 'Historical', 'Desert', 'City'];
        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}

