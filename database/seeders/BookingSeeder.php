<?php

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Hotel;

class BookingSeeder extends Seeder
{
    public function run()
    {
        Booking::create([
            'user_id' => User::inRandomOrder()->first()->id,
            'hotel_id' => Hotel::inRandomOrder()->first()->id,
            'check_in' => now()->addDays(3),
            'check_out' => now()->addDays(7)
        ]);
    }
}

