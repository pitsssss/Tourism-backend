<?php

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Hotel;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        Review::create([
            'user_id' => User::inRandomOrder()->first()->id,
            'reviewable_type' => Hotel::class,
            'reviewable_id' => Hotel::inRandomOrder()->first()->id,
            'rating' => 4,
            'comment' => 'Great experience!'
        ]);
    }
}

