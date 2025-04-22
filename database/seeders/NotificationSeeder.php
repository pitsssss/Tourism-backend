<?php

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        Notification::create([
            'user_id' => User::inRandomOrder()->first()->id,
            'title' => 'New Booking Available',
            'body' => 'Check out the latest offers in Palmyra!'
        ]);
    }
}

