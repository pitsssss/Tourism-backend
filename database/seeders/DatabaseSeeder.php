<?php

namespace Database\Seeders;

use App\Models\User;
use UserSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use BookingSeeder;
use CategorySeeder;
use ContactMessageSeeder;
use DestinationSeeder;
use FavoriteSeeder;
use Illuminate\Database\Seeder;
use NotificationSeeder;
use RestaurantSeeder;
use ReviewSeeder;

class DatabaseSeeder extends Seeder {
    /**
    * Seed the application's database.
    */

    public function run() {
        $this->call( [
            UserSeeder::class,
            CategorySeeder::class,
            DestinationSeeder::class,
            HotelSeeder::class,
            RestaurantSeeder::class,
            BookingSeeder::class,
            FavoriteSeeder::class,
            ReviewSeeder::class,
            NotificationSeeder::class,
            ContactMessageSeeder::class,
        ] );
    }

}
