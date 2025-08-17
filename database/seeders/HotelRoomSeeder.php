<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\Hotel_Room;
class HotelRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // مثال: نفترض عندك فندق واحد موجود بالفعل
        $hotel = Hotel::first(); // أو تختاري ID معين

        if ($hotel) {
            Hotel_Room::create([
                'hotel_id' => $hotel->id,
                'room_type' => 'single',
                'capacity' => 1,
                'price' => 50.00,
                'available_rooms' => 10,
                'total_rooms' => 10,
            ]);

            Hotel_Room::create([
                'hotel_id' => $hotel->id,
                'room_type' => 'double',
                'capacity' => 2,
                'price' => 80.00,
                'available_rooms' => 5,
                'total_rooms' => 5,
            ]);

            Hotel_Room::create([
                'hotel_id' => $hotel->id,
                'room_type' => 'suite',
                'capacity' => 4,
                'price' => 150.00,
                'available_rooms' => 2,
                'total_rooms' => 2,
            ]);
        }
    }
}
